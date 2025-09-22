<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel | @yield('title')</title>
    <!-- Icon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo_hotelNservices.jpg') }}">
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.layout.css') }}">
</head>

<body>
    <!-- Header Navigation -->
    <header class="shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light ">
            <div class="container-fluid ms-5 me-5">
                <!-- Logo and Text (Start) -->
                <div class="d-flex align-items-center">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ asset('img/logo_hotelNservices.jpg') }}" alt="KagayakuKin Yume Logo"
                            style="height: 40px;">
                    </a>
                    <span class="fs-4 navbar-title">KagayakuKin Yume Hotel</span>
                </div>

                <!-- Toggler for Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation and Buttons -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Navigation Links (Center) -->
                    <ul class="navbar-nav mx-auto gap-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                                href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('explore') ? 'active' : '' }}"
                                href="{{ route('explore') }}">Explore</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rooms') ? 'active' : '' }}"
                                href="{{ route('rooms') }}">Rooms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                                href="{{ route('about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                                href="{{ route('contact') }}">Contact</a>
                        </li>
                    </ul>

                    <!-- Booking and Auth Buttons (End) -->
                    <div class="d-flex gap-3 align-items-center">
                        <a href="{{ route('booking') }}" class="btn btn-primary">Book now</a>
                        @if (Auth::check())
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline" id="AuthBtn" data-form="login"><i
                                        class="bi bi-person-circle"></i>
                                    {{ Auth::user()->Role === 'Admin' ? '[ADMIN]' : '' }}
                                    {{ Auth::user()->Name ? '(' . Auth::user()->Name . ')' : '' }}
                                    {{ Auth::user()->email }}</button>
                            </form>
                        @else
                            <button class="btn btn-outline-secondary" id="AuthBtn" data-bs-toggle="modal"
                                data-bs-target="#authModal" data-form="login" disabled>Sign Up / Login</button>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-left" id="modalLeft">
                    <h3 id="modalLeftTitle">Welcome!</h3>
                    <p>Explore the luxury of KagayakuKin Yume Hotel.</p>
                    <button class="modal-btn" id="toggleButton" onclick="toggleForms('signup')">SIGN UP</button>
                </div>
                <div class="modal-right" id="modalRight">
                    <h3 id="modalRightTitle">Log in</h3>
                    <div id="loginForm">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <input type="email" class="modal-input @error('email') is-invalid @enderror"
                                placeholder="Email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="password" class="modal-input @error('password') is-invalid @enderror"
                                placeholder="Password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="modal-btn">LOGIN</button>
                        </form>
                    </div>
                    <div id="signupForm" style="display: none;">
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <input type="text" class="modal-input" placeholder="Name" name="Name"
                                value="{{ old('Name') }}" required>
                            @error('Name', 'register')
                                <div class="text-danger ms-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            <input type="text" class="modal-input" placeholder="Username" name="Username"
                                value="{{ old('Username') }}" required>
                            @error('Username', 'register')
                                <div class="text-danger ms-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            <input type="email" class="modal-input" placeholder="Email" name="email"
                                value="{{ old('email') }}" required>
                            @error('email', 'register')
                                <div class="text-danger ms-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            <input type="password" class="modal-input" placeholder="Password" name="password"
                                required>
                            @error('password', 'register')
                                <div class="text-danger ms-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            <input type="password" class="modal-input" placeholder="Confirm password"
                                name="password_confirmation" required>
                            @error('password_confirmation', 'register')
                                <div class="text-danger ms-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            <button type="submit" class="modal-btn">SIGN UP</button>
                        </form>
                    </div>
                    {{-- <p class="text-center">Or Sign in with social platform</p>
            <div class="social-login">
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-twitter"></i></a>
              <a href="#"><i class="bi bi-google"></i></a>
              <a href="#"><i class="bi bi-linkedin"></i></a>
            </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    @yield('content')

    @if (session('toast_success'))
        <div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 9999;">
            <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-center">
                        {{ session('toast_success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @elseif (session('toast_error'))
        <div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 9999;">
            <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-center">
                        {{ session('toast_error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @elseif(session('toast_info'))
        <div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 9999;">
            <div class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-center">
                        {{ session('toast_info') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5 EN">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="fs-5 mb-3">Contact</h3>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt me-2"></i> 123 Luxury Avenue, Cityscape, Country</li>
                        <li><i class="bi bi-telephone me-2"></i> +1 (555) 123-4567</li>
                        <li><i class="bi bi-envelope me-2"></i> info@luxuryhotel.com</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h3 class="fs-5 mb-3">Follow Us</h3>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-gray-600 mt-4">
            <p class="text-center text-gray-400">© 2025 Luxury Hotel. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.layout.js') }}" defer></script>
    <script>
        window.addEventListener("load", () => {
            @if ($errors->register->any())
                toggleForms('signup');
                new bootstrap.Modal(document.getElementById('authModal')).show();
            @elseif (session('LoginError'))
                new bootstrap.Modal(document.getElementById('authModal')).show();
            @endif
        });
    </script>
</body>

</html>
