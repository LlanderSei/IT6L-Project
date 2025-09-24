@extends('layouts.admin')

@section('title', 'Review Booking')

@section('content')
  <div class="container">
    <h1 class="h2 main-text">Review Booking</h1>
    <div class="card p-4">
      <h3>Booking Details</h3>
      <table class="table table-bordered">
        <tr>
          <th class="col-5">Reservation ID</th>
          <td>{{ $booking->ID }}</td>
        </tr>
        <tr>
          <th>User Name</th>
          <td>{{ $booking->user->Name }}</td>
        </tr>
        <tr>
          <th>Room Type</th>
          <td>{{ $booking->roomType->RoomTypeName }}</td>
        </tr>
        <tr>
          <th>Room Size</th>
          <td>{{ $booking->roomSize->RoomSizeName }}</td>
        </tr>
        <tr>
          <th>Check-In Date</th>
          <td>{{ Carbon\Carbon::parse($booking->CheckInDate)->format('Y-m-d') }}</td>
        </tr>
        <tr>
          <th>Check-Out Date</th>
          <td>{{ Carbon\Carbon::parse($booking->CheckOutDate)->format('Y-m-d') }}</td>
        </tr>
        <tr>
          <th>Number of Guests</th>
          <td>{{ $booking->NumberOfGuests }}</td>
        </tr>
        <tr>
          <th>Booking Status</th>
          <td>{{ $booking->BookingStatus }}</td>
        </tr>
        <tr>
          <th>Assigned Room</th>
          <td>{{ $booking->assignedRooms->first() ? $booking->assignedRooms->first()->room->RoomName : 'Not Assigned' }}
          </td>
        </tr>
      </table>

      <h3>Cost Details</h3>
      <table class="table table-bordered">
        <tr>
          <th class="col-5">Room Base Price</th>
          <td>₱{{ number_format($booking->costDetails->RoomBasePrice, 2) }}</td>
        </tr>
        <tr>
          <th>Nights</th>
          <td>{{ $booking->costDetails->Nights }}</td>
        </tr>
        <tr>
          <th>Subtotal</th>
          <td>₱{{ number_format($booking->costDetails->Subtotal, 2) }}</td>
        </tr>
        <tr>
          <th>Total Amount</th>
          <td>₱{{ number_format($booking->costDetails->TotalAmount, 2) }}</td>
        </tr>
      </table>

      <h3>Services Added</h3>
      @if ($booking->servicesAdded->isEmpty())
        <p>No services added.</p>
      @else
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="col-5">Service Name</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($booking->servicesAdded as $service)
              <tr>
                <td>{{ $service->ServiceName }}</td>
                <td>₱{{ number_format($service->ServicePrice, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif

      <h3>Payment Details</h3>
      @if ($booking->paymentInfo)
        <table class="table table-bordered">
          <tr>
            <th class="col-5">Total Amount</th>
            <td>₱{{ number_format($booking->paymentInfo->TotalAmount, 2) }}</td>
          </tr>
          @if ($booking->paymentInfo->PaymentStatus === 'Pending')
            <div class="alert alert-warning mt-2">
              This customer haven't submitted their payment yet.
            </div>
          @elseif($booking->paymentInfo->PaymentStatus === 'Submitted')
            <tr>
              <th>Payment Status</th>
              <td>{{ $booking->paymentInfo->PaymentStatus }}</td>
            </tr>
            <tr>
              <th>Payment Method</th>
              <td>{{ $booking->paymentInfo->PaymentMethod }}</td>
            </tr>
            @if ($booking->paymentInfo->PaymentMethod === 'Cash' && $booking->paymentInfo->cashpayment)
              <tr>
                <th>Cash Amount</th>
                <td>₱{{ number_format($booking->paymentInfo->cash->CashAmount, 2) }}</td>
              </tr>
            @elseif ($booking->paymentInfo->PaymentMethod === 'Card' && $booking->paymentInfo->cardpayment)
              <tr>
                <th>Card Holder Name</th>
                <td>{{ $booking->paymentInfo->card->CardHolderName ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Card Number</th>
                <td>{{ $booking->paymentInfo->card->CardNumber }}</td>
              </tr>
              <tr>
                <th>Expiry Date</th>
                <td>{{ $booking->paymentInfo->card->ExpiryDate }}</td>
              </tr>
              <tr>
                <th>CVC</th>
                <td>{{ $booking->paymentInfo->card->CVC ?? 'N/A' }}</td>
              </tr>
            @elseif ($booking->paymentInfo->PaymentMethod === 'EPayment' && $booking->paymentInfo->epayment)
              <tr>
                <th>Name</th>
                <td>{{ $booking->paymentInfo->epayment->Name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Provider</th>
                <td>{{ $booking->paymentInfo->epayment->Provider ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Number</th>
                <td>{{ $booking->paymentInfo->epayment->Number ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Amount</th>
                <td>₱{{ number_format($booking->paymentInfo->epayment->Amount, 2) ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Reference Number</th>
                <td>{{ $booking->paymentInfo->epayment->ReferenceNum ?? 'N/A' }}</td>
              </tr>
            @elseif ($booking->paymentInfo->PaymentMethod === 'Paypal' && $booking->paymentInfo->paypalpayment)
              <tr>
                <th>Name</th>
                <td>{{ $booking->paymentInfo->paypalpayment->Name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Amount</th>
                <td>{{ $booking->paymentInfo->paypalpayment->Amount ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Reference Number</th>
                <td>{{ $booking->paymentInfo->paypalpayment->ReferenceNum ?? 'N/A' }}</td>
              </tr>
            @elseif ($booking->paymentInfo->PaymentMethod === 'BankTransfer' && $booking->paymentInfo->bankTransferpayment)
              <tr>
                <th>Account Name</th>
                <td>{{ $booking->paymentInfo->bankTransferpayment->AccountName ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Account Number</th>
                <td>{{ $booking->paymentInfo->bankTransferpayment->AccountNumber ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Routing Number</th>
                <td>{{ $booking->paymentInfo->bankTransferpayment->RoutingNumber ?? 'N/A' }}</td>
              </tr>
            @endif
          @endif
        </table>
      @else
        <p>No payment details available.</p>
      @endif

      <h3>Actions</h3>
      <div class="d-flex flex-wrap gap-2">
        @if (
            $booking->BookingStatus === 'Pending' &&
                $booking->paymentInfo &&
                $booking->paymentInfo->PaymentStatus === 'Submitted')
          <form action="{{ route('admin.booking.accept', $booking->ID) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success">Accept Payment</button>
          </form>
          <form action="{{ route('admin.booking.reject', $booking->ID) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger">Reject Payment</button>
          </form>
        @endif
        @if ($booking->BookingStatus === 'Confirmed')
          <form action="{{ route('admin.booking.checkin', $booking->ID) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary">Check-In</button>
          </form>
          <form action="{{ route('admin.booking.cancel', $booking->ID) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger">Cancel Booking</button>
          </form>
        @endif
        @if ($booking->BookingStatus === 'Ongoing')
          <form action="{{ route('admin.booking.checkout', $booking->ID) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary">Check-Out</button>
          </form>
          <form action="{{ route('admin.booking.cancel', $booking->ID) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger">Cancel Booking</button>
          </form>
        @endif
        @if (in_array($booking->BookingStatus, ['Ended', 'Cancelled']))
          <p>No actions available.</p>
        @endif
      </div>

      <a href="{{ route('admin.guest') }}" class="btn btn-secondary mt-3">Back to Guest List</a>
    </div>
  </div>
@endsection
