@extends('layouts.app')

@section('title', 'Profile')

@section('content')
  <main class="container my-5">
    <!-- Hero Section -->
    <section class="mb-5 text-center">
      <h1 class="section-title display-4 fw-bold mb-3">My Profile</h1>
      <div class="d-flex justify-content-center mb-4">
        <div class="rounded-circle overflow-hidden" style="width: 150px; height: 150px; background-color: #f8cb45;">
          <img src="{{ asset('img/logo_hotelNservices.jpg') }}" class="img-fluid w-100 h-100" style="object-fit: cover;"
            alt="Profile Avatar">
        </div>
      </div>
    </section>

    <!-- User Details Section -->
    <section class="mb-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card facility-card">
            <div class="card-body">
              <h2 class="fs-4 fw-semibold mb-4">Personal Information</h2>
              <div class="row">
                <div class="col-md-6">
                  <p><strong>Name:</strong> {{ $user->Name }}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Username:</strong> {{ $user->Username }}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Email:</strong> {{ $user->email }}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Role:</strong> {{ $user->Role }}
                    @if ($user->Role === 'Admin')
                      <span class="badge bg-warning text-dark ms-2">Admin</span>
                    @endif
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Current Booking Section -->
    <section class="mb-5">
      <h2 class="display-6 fw-bold text-center mb-4 heading-underline">Current Booking</h2>
      @if ($currentBooking)
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="card facility-card">
              <div class="card-body">
                <table class="table table-bordered">
                  <tr>
                    <th class="col-5">Booking ID</th>
                    <td>{{ $currentBooking->ID }}</td>
                  </tr>
                  <tr>
                    <th class="col-5">Check-In Date</th>
                    <td>{{ \Carbon\Carbon::parse($currentBooking->CheckInDate)->format('F j, Y') }}</td>
                  </tr>
                  <tr>
                    <th class="col-5">Check-Out Date</th>
                    <td>{{ \Carbon\Carbon::parse($currentBooking->CheckOutDate)->format('F j, Y') }}</td>
                  </tr>
                  <tr>
                    <th class="col-5">Room Type</th>
                    <td>{{ $currentBooking->roomType->RoomTypeName }}</td>
                  </tr>
                  <tr>
                    <th class="col-5">Room Size</th>
                    <td>{{ $currentBooking->roomSize->RoomSizeName }}</td>
                  </tr>
                  <tr>
                    <th class="col-5">Number of Guests</th>
                    <td>{{ $currentBooking->NumberOfGuests }}</td>
                  </tr>
                  <tr>
                    <th class="col-5">Additional Services</th>
                    <td>
                      @if ($currentBooking->servicesAdded->isEmpty())
                        None
                      @else
                        {{ $currentBooking->servicesAdded->pluck('ServiceName')->implode(', ') }}
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <th class="col-5">Booking Status</th>
                    <td>{{ $currentBooking->BookingStatus }}</td>
                  </tr>
                  <tr>
                    <th class="col-5">Total Amount</th>
                    <td>₱{{ number_format($currentBooking->costDetails->TotalAmount ?? 0, 2) }}</td>
                  </tr>
                  @if ($currentBooking->assignedRooms->isNotEmpty())
                    <tr>
                      <th class="col-5">Assigned Room</th>
                      <td>{{ $currentBooking->assignedRooms->first()->room->RoomName ?? 'N/A' }}</td>
                    </tr>
                  @endif
                </table>
              </div>
            </div>
          </div>
        </div>
      @else
        <div class="text-center">
          <p class="lead">You have no current bookings.</p>
          <a href="{{ route('booking') }}" class="btn btn-primary">Book Now</a>
        </div>
      @endif
    </section>

    <!-- Booking History Section -->
    <section class="mb-5">
      <h2 class="display-6 fw-bold text-center mb-4 heading-underline">Booking History</h2>
      @if ($bookingHistory->isNotEmpty())
        <div class="row">
          @foreach ($bookingHistory as $booking)
            <div class="col-md-6 mb-4">
              <div class="card facility-card">
                <div class="card-body">
                  <h5 class="card-title">Booking #{{ $booking->ID }}</h5>
                  <p class="card-text">
                    <strong>Room:</strong> {{ $booking->roomType->RoomTypeName }} -
                    {{ $booking->roomSize->RoomSizeName }}<br>
                    <strong>Dates:</strong> {{ \Carbon\Carbon::parse($booking->CheckInDate)->format('M j, Y') }} to
                    {{ \Carbon\Carbon::parse($booking->CheckOutDate)->format('M j, Y') }}<br>
                    <strong>Status:</strong> {{ $booking->BookingStatus }}<br>
                    <strong>Total:</strong> ₱{{ number_format($booking->costDetails->TotalAmount ?? 0, 2) }}
                  </p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center">
          <p class="lead">No booking history available.</p>
        </div>
      @endif
    </section>
  </main>

  <style>
    .facility-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 1rem;
      overflow: hidden;
    }

    .facility-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
  </style>
@endsection
