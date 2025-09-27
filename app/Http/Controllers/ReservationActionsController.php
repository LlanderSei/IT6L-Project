<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssignedRoom;
use App\Models\Booking;
use App\Models\Room;
use App\Models\PaymentInfos;
use App\Models\UserLoyalty;
use App\Models\LoyaltyPointTransaction;
use App\Models\LoyaltyTier;
use App\Models\CashbackThreshold;

class ReservationActionsController extends Controller {
  public function __construct() {
    $this->middleware('RestrictByRole:Admin');
  }

  public function reviewBooking($id) {
    $booking = Booking::with([
      'user',
      'roomType',
      'roomSize',
      'servicesAdded',
      'costDetails',
      'assignedRooms.room',
      'paymentInfo' => function ($query) {
        $query->with(['cashpayment', 'cardpayment', 'epayment', 'paypalpayment', 'bankTransferpayment']);
      }
    ])->findOrFail($id);


    // dd($booking);

    return view('admin.reviewbooking', [
      'booking' => $booking,
    ]);
  }

  public function acceptPayment($id) {
    DB::beginTransaction();
    try {
      $booking = Booking::findOrFail($id);
      if ($booking->BookingStatus !== 'Pending' || !$booking->paymentInfo || $booking->paymentInfo->PaymentStatus !== 'Submitted') {
        return redirect()->route('admin.guest')->with('toast_error', 'Invalid booking or payment status.');
      }

      $room = Room::where('RoomTypeID', $booking->RoomTypeID)
        ->where('RoomSizeID', $booking->RoomSizeID)
        ->whereNotIn('ID', function ($q) use ($booking) {
          $q->select('RoomID')
            ->from('AssignedRooms')
            ->join('BookingDetails', 'AssignedRooms.BookingDetailID', '=', 'BookingDetails.ID')
            ->whereIn('BookingDetails.BookingStatus', ['Confirmed', 'Ongoing'])
            ->where('BookingDetails.CheckInDate', '<=', $booking->CheckOutDate)
            ->where('BookingDetails.CheckOutDate', '>=', $booking->CheckInDate);
        })
        ->first();

      if (!$room) {
        return redirect()->route('admin.guest')->with('toast_error', 'No available room for this booking.');
      }

      AssignedRoom::create([
        'BookingDetailID' => $booking->ID,
        'RoomID' => $room->ID,
        'Status' => 'Ongoing',
      ]);

      $booking->update(['BookingStatus' => 'Confirmed']);
      $booking->paymentInfo->update(['PaymentStatus' => 'Verified']);

      DB::commit();
      return redirect()->route('admin.guest')->with('toast_success', 'Payment verified and room assigned.');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('admin.guest')->with('toast_error', 'Failed to verify payment: ' . $e->getMessage());
    }
  }

  public function rejectPayment($id) {
    DB::beginTransaction();
    try {
      $booking = Booking::findOrFail($id);
      if ($booking->BookingStatus !== 'Pending' || !$booking->paymentInfo || $booking->paymentInfo->PaymentStatus !== 'Submitted') {
        return redirect()->route('admin.guest')->with('toast_error', 'Invalid booking or payment status.');
      }

      $booking->update(['BookingStatus' => 'Cancelled']);
      $booking->paymentInfo->update(['PaymentStatus' => 'Failed']);

      DB::commit();
      return redirect()->route('admin.guest')->with('toast_success', 'Payment rejected and booking cancelled.');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('admin.guest')->with('toast_error', 'Failed to reject payment: ' . $e->getMessage());
    }
  }

  public function checkIn($id) {
    DB::beginTransaction();
    try {
      $booking = Booking::findOrFail($id);
      if ($booking->BookingStatus !== 'Confirmed') {
        return redirect()->route('admin.guest')->with('toast_error', 'Booking must be confirmed to check in.');
      }

      $booking->update(['BookingStatus' => 'Ongoing', 'SettledCheckIn' => now()]);
      // Award loyalty points based on CashbackThresholds
      $user = $booking->user;
      $totalAmount = $booking->paymentInfo->TotalAmount;
      $threshold = CashbackThreshold::where('MinPaidAmount', '<=', $totalAmount)
        ->orderBy('MinPaidAmount', 'desc')
        ->first();
      $points = $threshold ? ($totalAmount * $threshold->CashbackPercentile / 100) : 0;

      if ($points > 0) {
        $userLoyalty = UserLoyalty::firstOrCreate(
          ['UserID' => $user->id],
          ['LoyaltyPoints' => 0, 'LoyaltyTierID' => null]
        );

        $userLoyalty->increment('LoyaltyPoints', $points);

        LoyaltyPointTransaction::create([
          'UserID' => $user->id,
          'Points' => $points,
          'TransactionType' => 'Earned',
          'PaymentInfoID' => $booking->paymentInfo->ID,
          'Description' => "Cashback for booking ID {$booking->ID}",
        ]);

        // Update loyalty tier
        $newTier = LoyaltyTier::where('MinPoints', '<=', $userLoyalty->LoyaltyPoints)
          ->orderBy('MinPoints', 'desc')
          ->first();

        if ($newTier && $newTier->ID !== $userLoyalty->LoyaltyTierID) {
          $userLoyalty->update(['LoyaltyTierID' => $newTier->ID]);
        }
      }

      DB::commit();
      return redirect()->route('admin.guest')->with('toast_success', 'Guest checked in successfully.' . ($points > 0 ? ' Awarded ' . $points . ' loyalty points.' : ''));
    } catch (\Exception $e) {
      DB::rollBack();
      dd($e);
      return redirect()->route('admin.guest')->with('toast_error', 'Failed to check in: ' . $e->getMessage());
    }
  }

  public function checkOut($id) {
    DB::beginTransaction();
    try {
      $booking = Booking::findOrFail($id);
      if ($booking->BookingStatus !== 'Ongoing') {
        return redirect()->route('admin.guest')->with('toast_error', 'Booking must be ongoing to check out.');
      }

      $booking->update(['BookingStatus' => 'Ended', 'SettledCheckOut' => now()]);
      $booking->assignedRooms()->update(['Status' => 'Ended']);

      DB::commit();
      return redirect()->route('admin.guest')->with('toast_success', 'Guest checked out successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('admin.guest')->with('toast_error', 'Failed to check out: ' . $e->getMessage());
    }
  }

  public function cancelBooking($id) {
    DB::beginTransaction();
    try {
      $booking = Booking::findOrFail($id);
      if (!in_array($booking->BookingStatus, ['Pending', 'Confirmed', 'Ongoing'])) {
        return redirect()->route('admin.guest')->with('toast_error', 'Booking cannot be cancelled in its current status.');
      }

      $booking->update(['BookingStatus' => 'Cancelled']);
      if ($booking->paymentInfo && $booking->paymentInfo->PaymentStatus === 'Verified') {
        $booking->paymentInfo->update(['PaymentStatus' => 'Failed']);
      }
      $booking->assignedRooms()->update(['Status' => 'Ended']);

      DB::commit();
      return redirect()->route('admin.guest')->with('toast_success', 'Booking cancelled successfully.');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->route('admin.guest')->with('toast_error', 'Failed to cancel booking: ' . $e->getMessage());
    }
  }
}
