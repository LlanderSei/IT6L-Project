@extends('layouts.app')

@section('title', 'About')

@section('content')
    <main class="container my-5">
        <!-- Hero Section -->
        <section class="mb-5 text-center">
            <h1 class="section-title display-4 fw-bold mb-3">About KagayakuKin Yume Hotel</h1>
            <div class="rounded overflow-hidden mb-4" style="height: 300px;">
                <img src="{{ asset('img/heroimage.jpg') }}" class="img-fluid w-100 h-100" style="object-fit: cover;"
                    alt="About KagayakuKin Yume Hotel">
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="lead">KagayakuKin Yume Hotel represents the pinnacle of luxury hospitality in Davao City.
                        Established in 2023, our hotel combines elegant design with exceptional service, creating
                        unforgettable
                        experiences for our guests.</p>
                </div>
            </div>
        </section>

        <!-- About Our Hotel Section -->
        <section class="mb-5">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="section-title display-6 fw-bold mb-3">Our Vision</h2>
                    <p class="mb-4">To be the premier luxury destination in Davao City, known for exceptional hospitality
                        that
                        honors Filipino traditions while embracing modern luxury.</p>

                    <h2 class="section-title display-6 fw-bold mb-3">Our Mission</h2>
                    <p class="mb-4">To provide unparalleled hospitality experiences by combining luxurious accommodations,
                        exceptional dining, and personalized service that exceeds our guests' expectations.</p>
                </div>
                <div class="col-md-6">
                    <div class="rounded overflow-hidden h-100">
                        <img src="{{ asset('img/pool.jpg') }}" class="img-fluid w-100 h-100" style="object-fit: cover;"
                            alt="Hotel Pool">
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5 text-center">
            <h2 class="section-title display-5 fw-bold mb-4">Meet Our Team</h2>
            <p class="lead mb-5">The talented individuals behind KagayakuKin Yume Hotel's digital experience</p>
        </section>

        <!-- Team Members Section -->
        <section class="mb-5">
            <!-- Team Member 1 -->
            <div class="row align-items-center mb-5">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="rounded-4 overflow-hidden shadow-sm" style="height: 400px;">
                        <img src="{{ asset('img/Evander.jpg') }}" alt="Evander Amorcillo" class="img-fluid w-100 h-100"
                            style="object-fit: cover;">
                    </div>
                    <h3 class="section-title fs-4 mt-3">Evander Amorcillo</h3>
                    <p class="text-muted">Database Architect & Quality Assurance</p>
                </div>
                <div class="col-md-8">
                    <h2 class="section-title display-6 fw-bold mb-3">Database Architecture & Quality Assurance</h2>
                    <p class="mb-3">Evander is the cornerstone of our data management and quality control. With his
                        meticulous
                        attention to detail and passion for efficiency, he designed and implemented the robust database
                        infrastructure that powers our entire hotel management system.</p>
                    <p class="mb-3">As our QA specialist, he ensures every feature works flawlessly before reaching our
                        clients,
                        maintaining the highest standards of quality in all aspects of our digital experience.</p>
                    <h4 class="section-title fw-semibold mb-2">Key Responsibilities:</h4>
                    <ul class="list-group list-group-flush list-group-color">
                        <li class="list-group-item">Designing and optimizing database schema for maximum performance</li>
                        <li class="list-group-item">Implementing data validation and integrity measures</li>
                        <li class="list-group-item">Conducting comprehensive quality testing across all platform features
                        </li>
                        <li class="list-group-item">Ensuring seamless data flow between frontend and backend systems</li>
                    </ul>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="row align-items-center mb-5">
                <div class="col-md-4 order-md-2 mb-4 mb-md-0">
                    <div class="rounded-4 overflow-hidden shadow-sm" style="height: 400px;">
                        <img src="{{ asset('img/Rodzel.jpg') }}" alt="Rodzel John Te" class="img-fluid w-100 h-100"
                            style="object-fit: cover;">
                    </div>
                    <h3 class="section-title fs-4 mt-3">Rodzel John Te</h3>
                    <p class="text-muted">Frontend & Backend Developer</p>
                </div>
                <div class="col-md-8 order-md-1">
                    <h2 class="section-title display-6 fw-bold mb-3">User Interface & System Development</h2>
                    <p class="mb-3">Rodzel brings creativity and technical expertise to our development team. As our
                        primary
                        frontend developer, he crafted the elegant, user-friendly interface that guests interact with when
                        booking
                        their stays and exploring our amenities.</p>
                    <p class="mb-3">His skills extend to backend development as well, where he collaborates closely with
                        the team
                        to implement core functionality and ensure seamless integration between all system components.</p>
                    <h4 class="section-title fw-semibold mb-2">Key Responsibilities:</h4>
                    <ul class="list-group list-group-flush list-group-color">
                        <li class="list-group-item">Building responsive, accessible user interfaces using modern web
                            technologies</li>
                        <li class="list-group-item">Implementing interactive features and animations that enhance user
                            experience</li>
                        <li class="list-group-item">Developing backend logic for reservation processing and user management
                        </li>
                        <li class="list-group-item">Optimizing website performance across all devices</li>
                    </ul>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="row align-items-center">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="rounded-4 overflow-hidden shadow-sm" style="height: 400px;">
                        <img src="{{ asset('img/Llander.jpg') }}" alt="Christopher Llander L. Villacino"
                            class="img-fluid w-100 h-100" style="object-fit: cover;">
                    </div>
                    <h3 class="section-title fs-4 mt-3">Christopher Llander L. Villacino</h3>
                    <p class="text-muted">Backend Developer & Security Specialist</p>
                </div>
                <div class="col-md-8">
                    <h2 class="section-title display-6 fw-bold mb-3">System Architecture & Security</h2>
                    <p class="mb-3">Llander is the backbone of our technical infrastructure. As our lead backend
                        developer, he
                        designed and implemented the complex systems that power our hotel's digital operations, from
                        reservations to
                        inventory management.</p>
                    <p class="mb-3">His expertise in cybersecurity ensures that our guests' data remains protected at all
                        times,
                        implementing robust security protocols and encryption methods that meet the highest industry
                        standards.</p>
                    <h4 class="section-title fw-semibold mb-2">Key Responsibilities:</h4>
                    <ul class="list-group list-group-flush list-group-color">
                        <li class="list-group-item">Developing secure API endpoints and server-side logic</li>
                        <li class="list-group-item">Implementing comprehensive authentication and authorization systems</li>
                        <li class="list-group-item">Conducting regular security audits and vulnerability assessments</li>
                        <li class="list-group-item">Ensuring compliance with data protection regulations</li>
                        <li class="list-group-item">Optimizing backend performance for fast response times</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Our Values Section -->
        <section class="mb-5 py-5 bg-transparent rounded-4">
            <div class="container py-4 px-4 rounded-4" style="background-color: floralwhite;">
                <h2 class="section-title display-6 fw-bold text-center mb-4 position-relative d-inline-block mx-auto">
                    Our Core Values
                    <span class="underline"></span>
                </h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 facility-card">
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-star-fill text-warning fs-1"></i>
                                </div>
                                <h3 class="fs-4 mb-3">Excellence</h3>
                                <p>We strive for excellence in everything we do, from the smallest details to the grandest
                                    experiences.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 facility-card">
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-people-fill text-warning fs-1"></i>
                                </div>
                                <h3 class="fs-4 mb-3">Hospitality</h3>
                                <p>We treat every guest like family, with warmth, respect, and genuine care.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 facility-card">
                            <div class="card-body text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-lightbulb-fill text-warning fs-1"></i>
                                </div>
                                <h3 class="fs-4 mb-3">Innovation</h3>
                                <p>We continuously seek new ways to enhance our services and exceed expectations.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
