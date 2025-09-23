<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link rel="stylesheet" href="{{ asset('css/modal.auth.css') }}"> <!-- Auth modal style -->
  </head>

  <body>
    <!-- Header Navigation -->
    <header class="shadow-sm">
      <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid ms-5 me-5">
          <!-- Logo and Text (Start) -->
          <div class="d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
              <img src="{{ asset('img/logo_hotelNservices.jpg') }}" alt="KagayakuKin Yume Logo" style="height: 40px;">
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
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
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
            <div class="d-flex gap-3 align-items-center" id="authSection">
              <a href="{{ route('booking') }}" class="btn btn-primary">Book now</a>
              @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                  @csrf
                  <button type="submit" class="btn btn-outline" id="AuthBtn" data-form="login"><i
                      class="bi bi-person-circle"></i>
                    {{ Auth::user()->Role === 'Admin' ? '[ADMIN]' : '' }}
                    {{ Auth::user()->Name ? '(' . Auth::user()->Name . ')' : '' }}
                    {{ Auth::user()->email }}</button>
                </form>
              @else
                <button class="btn btn-outline-secondary" id="AuthBtn" data-bs-toggle="modal"
                  data-bs-target="#authModal" data-form="login">Sign Up / Login</button>
              @endif
            </div>
          </div>
        </div>
      </nav>
    </header>

    <!-- Updated Auth Modal -->
    <div class="modal fade auth-modal" id="authModal" tabindex="-1" aria-labelledby="authModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginForm"
                  type="button">Login</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signupForm"
                  type="button">Sign Up</button>
              </li>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="tab-content">
              <div class="tab-pane fade show active" id="loginForm">
                <h3 class="text-center mb-4">Log in</h3>
                <form id="loginFormSubmit" action="{{ route('login') }}" method="POST">
                  @csrf
                  <input type="email" class="modal-input" placeholder="Email" name="email"
                    value="{{ old('email') }}" required>
                  <div class="invalid-feedback" id="login-email-error"></div>
                  <input type="password" class="modal-input" placeholder="Password" name="password" required>
                  <div class="invalid-feedback" id="login-password-error"></div>
                  <button type="submit" class="modal-btn">LOGIN</button>
                </form>
              </div>
              <div class="tab-pane fade" id="signupForm">
                <h3 class="text-center mb-4">Sign Up</h3>
                <form id="signupFormSubmit" action="{{ route('register') }}" method="POST">
                  @csrf
                  <input type="text" class="modal-input" placeholder="Name" name="Name"
                    value="{{ old('Name') }}" required>
                  <div class="invalid-feedback" id="signup-Name-error"></div>
                  <input type="text" class="modal-input" placeholder="Username" name="Username"
                    value="{{ old('Username') }}" required>
                  <div class="invalid-feedback" id="signup-Username-error"></div>
                  <input type="email" class="modal-input" placeholder="Email" name="email"
                    value="{{ old('email') }}" required>
                  <div class="invalid-feedback" id="signup-email-error"></div>
                  <input type="password" class="modal-input" placeholder="Password" name="password" required>
                  <div class="invalid-feedback" id="signup-password-error"></div>
                  <input type="password" class="modal-input" placeholder="Confirm password"
                    name="password_confirmation" required>
                  <div class="invalid-feedback" id="signup-password_confirmation-error"></div>
                  <button type="submit" class="modal-btn">SIGN UP</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- Toast Container -->
    <div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index: 9999;" id="toastContainer">
      @if (session('toast_success'))
        <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
          aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
          <div class="d-flex">
            <div class="toast-body text-center">
              {{ session('toast_success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
              aria-label="Close"></button>
          </div>
        </div>
      @elseif (session('toast_error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive"
          aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
          <div class="d-flex">
            <div class="toast-body text-center">
              {{ session('toast_error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
              aria-label="Close"></button>
          </div>
        </div>
      @elseif(session('toast_info'))
        <div class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive"
          aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
          <div class="d-flex">
            <div class="toast-body text-center">
              {{ session('toast_info') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
              aria-label="Close"></button>
          </div>
        </div>
      @endif
    </div>

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
      // Initialize toasts on page load
      window.addEventListener("load", () => {
        const toastContainer = document.getElementById('toastContainer');
        const toasts = toastContainer.querySelectorAll('.toast');
        toasts.forEach(toast => {
          new bootstrap.Toast(toast).show();
        });

        @if ($errors->register->any())
          document.getElementById('signup-tab').click();
          new bootstrap.Modal(document.getElementById('authModal')).show();
        @elseif (session('LoginError'))
          new bootstrap.Modal(document.getElementById('authModal')).show();
        @endif
      });

      // Handle form submissions with AJAX
      document.addEventListener('DOMContentLoaded', () => {
        const loginForm = document.getElementById('loginFormSubmit');
        const signupForm = document.getElementById('signupFormSubmit');
        const logoutForm = document.getElementById('logoutForm');
        const authSection = document.getElementById('authSection');
        const authModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('authModal'));

        // Handle Login Form Submission
        if (loginForm) {
          loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            try {
              const response = await fetch(loginForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
              });
              const data = await response.json();

              // Clear previous errors
              document.querySelectorAll('#loginForm .invalid-feedback').forEach(el => el.textContent = '');
              document.querySelectorAll('#loginForm .modal-input').forEach(el => el.classList.remove('is-invalid'));

              if (data.status === 'success') {
                // Show success toast
                showToast('success', data.message);
                // Update auth section
                updateAuthSection(data.user);
                // Close modal
                authModal.hide();
                // Redirect admin users
                if (data.redirect) {
                  window.location.href = data.redirect;
                }
              } else {
                // Show error toast and display validation errors
                showToast('error', data.message || 'Login failed. Check your credentials.');
                if (data.errors) {
                  Object.keys(data.errors).forEach(key => {
                    const errorElement = document.getElementById(`login-${key}-error`);
                    const inputElement = loginForm.querySelector(`[name="${key}"]`);
                    if (errorElement && inputElement) {
                      errorElement.textContent = data.errors[key][0];
                      inputElement.classList.add('is-invalid');
                    }
                  });
                }
              }
            } catch (error) {
              showToast('error', 'An error occurred. Please try again.');
            }
          });
        }

        // Handle Signup Form Submission
        if (signupForm) {
          signupForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(signupForm);
            try {
              const response = await fetch(signupForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
              });
              const data = await response.json();

              // Clear previous errors
              document.querySelectorAll('#signupForm .invalid-feedback').forEach(el => el.textContent = '');
              document.querySelectorAll('#signupForm .modal-input').forEach(el => el.classList.remove('is-invalid'));

              if (data.status === 'success') {
                // Show success toast
                showToast('success', data.message);
                // Update auth section
                updateAuthSection(data.user);
                // Close modal
                authModal.hide();
              } else {
                // Show error toast and display validation errors
                showToast('error', data.message || 'Registration failed. Please check your inputs.');
                if (data.errors) {
                  Object.keys(data.errors).forEach(key => {
                    const errorElement = document.getElementById(`signup-${key}-error`);
                    const inputElement = signupForm.querySelector(`[name="${key}"]`);
                    if (errorElement && inputElement) {
                      errorElement.textContent = data.errors[key][0];
                      inputElement.classList.add('is-invalid');
                    }
                  });
                }
              }
            } catch (error) {
              showToast('error', 'An error occurred. Please try again.');
            }
          });
        }

        // Handle Logout Form Submission
        if (logoutForm) {
          logoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
              const response = await fetch(logoutForm.action, {
                method: 'POST',
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
              });
              const data = await response.json();

              if (data.status === 'success') {
                // Show success toast
                showToast('success', data.message);
                // Update auth section to show login button
                authSection.innerHTML = `
                  <a href="{{ route('booking') }}" class="btn btn-primary">Book now</a>
                  <button class="btn btn-outline-secondary" id="AuthBtn" data-bs-toggle="modal"
                    data-bs-target="#authModal" data-form="login">Sign Up / Login</button>
                `;
                // Update CSRF token
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                document.querySelector('#loginFormSubmit input[name="_token"]').value = data.csrf_token;
                document.querySelector('#signupFormSubmit input[name="_token"]').value = data.csrf_token;
              } else {
                showToast('error', data.message || 'Logout failed. Please try again.');
              }
            } catch (error) {
              showToast('error', 'An error occurred. Please try again.');
            }
          });
        }

        // Function to show toast messages
        function showToast(type, message) {
          const toastContainer = document.getElementById('toastContainer');
          const bgClass = type === 'success' ? 'text-bg-success' : type === 'error' ? 'text-bg-danger' :
            'text-bg-warning';
          const toastHTML = `
            <div class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive"
              aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
              <div class="d-flex">
                <div class="toast-body text-center">
                  ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                  aria-label="Close"></button>
              </div>
            </div>
          `;
          toastContainer.innerHTML = toastHTML;
          const toast = toastContainer.querySelector('.toast');
          new bootstrap.Toast(toast).show();
        }

        // Function to update auth section after login/signup
        function updateAuthSection(user) {
          authSection.innerHTML = `
            <a href="{{ route('booking') }}" class="btn btn-primary">Book now</a>
            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
              <button type="submit" class="btn btn-outline" id="AuthBtn" data-form="login"><i
                  class="bi bi-person-circle"></i>
                ${user.Role === 'Admin' ? '[ADMIN]' : ''}
                ${user.Name ? '(' + user.Name + ')' : ''}
                ${user.email}
              </button>
            </form>
          `;
          // Reattach logout event listener
          const newLogoutForm = document.getElementById('logoutForm');
          if (newLogoutForm) {
            newLogoutForm.addEventListener('submit', async (e) => {
              e.preventDefault();
              try {
                const response = await fetch(newLogoutForm.action, {
                  method: 'POST',
                  headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                  },
                });
                const data = await response.json();

                if (data.status === 'success') {
                  showToast('success', data.message);
                  authSection.innerHTML = `
                    <a href="{{ route('booking') }}" class="btn btn-primary">Book now</a>
                    <button class="btn btn-outline-secondary" id="AuthBtn" data-bs-toggle="modal"
                      data-bs-target="#authModal" data-form="login">Sign Up / Login</button>
                  `;
                  // Update CSRF token
                  document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                  document.querySelector('#loginFormSubmit input[name="_token"]').value = data.csrf_token;
                  document.querySelector('#signupFormSubmit input[name="_token"]').value = data.csrf_token;
                } else {
                  showToast('error', data.message || 'Logout failed. Please try again.');
                }
              } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
              }
            });
          }
        }
      });
    </script>
  </body>

</html>
