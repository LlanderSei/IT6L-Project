<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\LoyaltyTier;
use App\Models\Service;
use App\Models\PaymentInfos;
use Illuminate\Support\Facades\Auth;
use App\Models\RoomSize;
use App\Models\RoomType;
use App\Models\UserLoyalty;
use Illuminate\Http\Request;

class PageController extends Controller {
  public function home() {
    return view('customer.home', ['title' => 'Home', 'Rooms' => RoomType::all()]);
  }

  public function explore() {
    return view('customer.explore', ['title' => 'Explore', 'Services' => Service::get()]);
  }

  public function rooms() {
    return view('customer.rooms', ['title' => 'Rooms', 'Rooms' => RoomType::get()]);
  }

  public function about() {
    return view('customer.about', ['title' => 'About']);
  }

  public function contact() {
    return view('customer.contact', ['title' => 'Contact']);
  }

  public function booking(Request $request) {
    $activeBooking = Booking::where('UserID', Auth::id())
      ->whereIn('BookingStatus', ['Confirmed', 'Ongoing'])
      ->exists();

    if ($activeBooking) {
      return redirect()->route('reservation')->with('toast_info', 'You have an active reservation.');
    }

    $ChosenRoom = $request->input('ChosenRoom');

    // Validate ChosenRoom against RoomType names
    $validRoomTypes = RoomType::pluck('RoomTypeName')->toArray();
    if ($ChosenRoom && !in_array($ChosenRoom, $validRoomTypes)) {
      return redirect()->route('rooms')->with('toast_error', 'Invalid room type selected.');
    }

    return view('customer.booking', [
      'title' => 'Booking',
      'RoomSizes' => RoomSize::get(),
      'RoomTypes' => RoomType::get(),
      'Services' => Service::where('ServiceStatus', 'Available')->get(),
      'HasPendingBooking' => Booking::where('UserID', Auth::id())
        ->where('BookingStatus', 'Pending')
        ->orderBy('created_at', 'desc')
        ->first(),
      'ChosenRoom' => $ChosenRoom,
    ]);
  }

  public function Reservation() {
    $reservation = Booking::with(['roomType', 'roomSize', 'servicesAdded', 'costDetails', 'assignedRooms.room'])
      ->where('UserID', Auth::id())
      ->whereIn('BookingStatus', ['Confirmed', 'Ongoing'])
      ->whereHas('paymentInfo', function ($query) {
        $query->where('PaymentStatus', 'Verified');
      })
      ->whereHas('assignedRooms', function ($query) {
        $query->whereNotNull('RoomID');
      })
      ->orderBy('created_at', 'desc')
      ->first();

    return view('customer.reservation', [
      'title' => 'Reservation',
      'reservation' => $reservation,
    ]);
  }

  public function profile() {
    $user = Auth::user();

    $currentBooking = Booking::with(['roomType', 'roomSize', 'servicesAdded', 'costDetails', 'assignedRooms.room', 'paymentInfo'])
      ->where('UserID', Auth::id())
      ->whereIn('BookingStatus', ['Pending', 'Confirmed', 'Ongoing'])
      ->orderBy('created_at', 'desc')
      ->first();

    $bookingHistory = Booking::with(['roomType', 'roomSize', 'servicesAdded', 'costDetails', 'paymentInfo'])
      ->where('UserID', Auth::id())
      ->whereIn('BookingStatus', ['Ended', 'Cancelled'])
      ->orderBy('created_at', 'desc')
      ->get();

    return view('customer.profile', [
      'title' => 'Profile',
      'user' => $user,
      'currentBooking' => $currentBooking,
      'bookingHistory' => $bookingHistory,
    ]);
  }
}
