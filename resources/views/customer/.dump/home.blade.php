@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <main class="container my-5">
        <!-- Hero Section -->
        <section class="row align-items-center mb-5">
            <div class="col-md-6">
                <p class="logo fs-4 mb-2">KagayakuKin Yume Hotel</p>
                <h1 class="display-4 fw-bold mb-4">Experience Luxury<br>Like Never Before</h1>
                <p class="text-muted mb-4">Indulge in unparalleled comfort and sophistication at KagayakuKin Yume Hotel,
                    where
                    every moment is crafted for your delight.</p>
                <!-- Modified: Update hero section button to trigger modal or redirect -->
                @auth
                    <a href="{{ route('rooms') }}" class="btn btn-primary">Book Now</a>
                @else
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginRegisterModal">Book Now</button>
                @endauth
                <!-- End Modified -->
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="bg-light text-center" style="height: 400px; overflow: hidden;">
                    <img src="{{ asset('/img/rooms/Paint-RoomFamily.png') }}" class="img-fluid"
                        style="width: 100%; height: 100%; object-fit: cover;" alt="KagayakuKin Yume Hotel Lobby">
                </div>
            </div>
        </section>

        <!-- Facilities Section -->
        <section class="mb-5">
            <h2 class="display-6 fw-bold text-center mb-3">Our Facilities</h2>
            <p class="text-center text-muted mb-5">We offer modern (5 star) hotel facilities for your comfort.</p>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title fs-5">Luxurious Rooms</h3>
                            <p class="card-text">Elegant and comfortable rooms with modern amenities.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title fs-5">Fine Dining</h3>
                            <p class="card-text">Exquisite cuisine prepared by world-class chefs.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title fs-5">Swimming Pool</h3>
                            <p class="card-text">Refreshing pool with stunning views and poolside service.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title fs-5">Spa & Wellness</h3>
                            <p class="card-text">Rejuvenating treatments and therapies for complete relaxation.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title fs-5">Fitness Center</h3>
                            <p class="card-text">State-of-the-art equipment and personal training options.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title fs-5">Concierge Service</h3>
                            <p class="card-text">24/7 assistance for all your needs during your stay.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Rooms Section -->
        <section class="bg-warning bg-opacity-25 py-5 mb-5">
            <h2 class="display-6 fw-bold text-center mb-3">Luxurious Rooms</h2>
            <p class="text-center text-muted mb-5">All rooms are designed for your comfort.</p>
            <div class="row row-cols-1 row-cols-md-3 g-4">

                @forelse($Rooms as $room)
                    <div class="col">
                        <div class="card h-100">
                            <div class="bg-light text-center" style="height: 200px; overflow: hidden;">
                                <img src="{{ asset($room->ImagePathname) }}" class="img-fluid"
                                    style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $room->ImageName }}">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title fs-5">{{ $room->RoomName }}</h3>
                                <p class="card-text">{{ $room->RoomDescription }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">₱{{ $room->RoomPrice }}/night</span>
                                    <!-- Modified: Change link to button that triggers modal -->
                                    @auth
                                        <form action="{{ route('checkout', $room->ID) }}" method="GET">
                                            <button class="btn btn-primary room-book-btn">Book Now</button>
                                        </form>
                                    @else
                                        <button class="btn btn-primary room-book-btn" data-bs-toggle="modal"
                                            data-bs-target="#authModal" data-form="login">Book Now</button>
                                    @endauth

                                    <!-- End Modified -->
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title fs-5">No Rooms Available</h3>
                                <p class="card-text">No rooms available at this moment, please come back later.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Room Details Modal -->
        <!-- Modified: Add reusable room details modal -->
        {{-- <div class="modal fade" id="roomDetailsModal" tabindex="-1" aria-labelledby="roomDetailsModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="roomDetailsModalLabel">Room Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <img id="roomModalImage" src="" class="img-fluid"
                  style="width: 100%; height: 250px; object-fit: cover;" alt="">
              </div>
              <div class="col-md-6">
                <h3 id="roomModalName" class="fs-4"></h3>
                <p id="roomModalDescription" class="text-muted"></p>
                <p><strong>Price:</strong> ₱<span id="roomModalPrice"></span>/night</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="roomModalBookBtn" data-room-id="">Book Now</button>
          </div>
        </div>
      </div>
    </div> --}}
        <!-- End Modified -->

        <!-- Login/Register Modal -->
        <!-- Modified: Add login/register modal for unauthenticated users -->

        <!-- Button trigger modal -->

        <!-- MODAL I'M MODIFYING -->
        {{-- <div class="modal fade" id="RoomDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div> --}}
    </main>

    <!-- Modified: Add JavaScript for modal functionality -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const roomDetailsModal = document.getElementById('roomDetailsModal');
                const loginRegisterModal = document.getElementById('loginRegisterModal');
                const roomModalImage = document.getElementById('roomModalImage');
                const roomModalName = document.getElementById('roomModalName');
                const roomModalDescription = document.getElementById('roomModalDescription');
                const roomModalPrice = document.getElementById('roomModalPrice');
                const roomModalBookBtn = document.getElementById('roomModalBookBtn');

                // Store authentication status
                const isAuthenticated = @json(auth()->check());

                // Handle room button click to populate modal
                document.querySelectorAll('.room-book-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const roomId = this.dataset.roomId;
                        const roomName = this.dataset.roomName;
                        const roomDescription = this.dataset.roomDescription;
                        const roomPrice = this.dataset.roomPrice;
                        const roomImage = this.dataset.roomImage;
                        const roomImageAlt = this.dataset.roomImageAlt;

                        // Populate modal content
                        roomModalImage.src = roomImage;
                        roomModalImage.alt = roomImageAlt;
                        roomModalName.textContent = roomName;
                        roomModalDescription.textContent = roomDescription;
                        roomModalPrice.textContent = roomPrice;
                        roomModalBookBtn.dataset.roomId = roomId;
                    });
                });

                // Handle modal "Book Now" button click
                roomModalBookBtn.addEventListener('click', function() {
                    const roomId = this.dataset.roomId;
                    if (isAuthenticated) {
                        // Redirect to checkout route
                        window.location.href = `/rooms/${roomId}/checkout`;
                    } else {
                        // Close room details modal and show login/register modal
                        const bsRoomModal = bootstrap.Modal.getInstance(roomDetailsModal);
                        bsRoomModal.hide();
                        const bsLoginModal = new bootstrap.Modal(loginRegisterModal);
                        bsLoginModal.show();
                    }
                });
            });
        </script>
    @endpush
    <!-- End Modified -->
@endsection
