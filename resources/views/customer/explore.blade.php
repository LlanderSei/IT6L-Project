@extends('layouts.app')

@section('title', 'Explore')

@section('content')
  <!-- Hero Section - Full width with background image -->
  <section class="position-relative mb-5" style="height: 400px; overflow: hidden;">
    <div class="position-absolute w-100 h-100" style="z-index: -1;">
      <img src="{{ asset('img/services/mainhotel.jpg') }}" class="w-100 h-100" style="object-fit: cover;"
        alt="Hotel Panorama">
      <div class="position-absolute top-0 left-0 w-100 h-100 bg-dark" style="opacity: 0.5;"></div>
    </div>
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center">
      <h1 class="display-4 fw-bold mb-3 text-white heading-underline">Take a Tour</h1>
      <p class="lead text-white">Discover our world-class accommodations and amenities for an unforgettable stay.</p>
    </div>
  </section>

  <main class="container my-5">
    <!-- Tour Section -->
    <section class="mb-5">
      <div class="row g-4">

        @forelse($Services as $Service)
          <div class="col-12">
            <div class="card">
              <div class="bg-light text-center" style="height: 300px; overflow: hidden;">
                <img src="{{ asset($Service->ImagePathname) }}" class="img-fluid"
                  style="width: 100%; height: 100%; object-fit: cover;" alt="Fitness Center">
              </div>
              <div class="card-body text-center">
                <h3 class="card-title fs-5 ">{{ $Service->ServiceName }}</h3>
                <p class="card-text">{!! nl2br(e($Service->ServiceDescription)) !!}</p>
              </div>
            </div>
          </div>
        @empty<div class="col-12">
            <div class="card">
              <div class="card-body text-center">
                <h3 class="card-title fs-5 text-warning">No Service Available</h3>
                <p class="card-text">No services are being offered right now, please come back later.</p>
              </div>
            </div>
          </div>
        @endforelse
      </div>
    </section>
  </main>
@endsection
