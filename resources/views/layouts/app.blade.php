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
      <nav class="navbar navbar-expand-lg navbar-light bg-blue-100">
        <div class="container-fluid ms-5 me-5">
          <!-- Logo and Text (Start) -->
          <div class="d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
              <img src="{{ asset('img/logo_hotelNservices.jpg') }}" alt="KagayakuKin Yume Logo" style="height: 40px;">
            </a>
            <a class="link-underline link-underline-opacity-0" href="{{ route('home') }}">
              <span class="fs-4 navbar-title">KagayakuKin Yume Resort</span>
            </a>
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
                <div class="dropdown">
                  <button class="btn btn-outline dropdown-toggle" type="button" id="userDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    {{ Auth::user()->Role === 'Admin' ? Auth::user()->Role . ' | ' : '' }}
                    {{ Auth::user()->Name ? Auth::user()->Name . ' |' : '' }}
                    {{ Auth::user()->email }}
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    @if (Auth::user()->Role === 'Admin')
                      <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                    @endif
                    <li>
                      <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                      </form>
                    </li>
                  </ul>
                </div>
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
                  <input type="password" class="modal-input" placeholder="Password" name="password" required>
                  <button type="submit" class="modal-btn">Log in</button>
                </form>
                <div class="text-center mt-3">
                  <a href="#">Forgot password?</a>
                </div>
              </div>
              <div class="tab-pane fade" id="signupForm">
                <h3 class="text-center mb-4">Sign up</h3>
                <form id="signupFormSubmit" action="{{ route('register') }}" method="POST">
                  @csrf
                  <input type="text" class="modal-input" placeholder="Name" name="Name"
                    value="{{ old('Name') }}" required>
                  <input type="email" class="modal-input" placeholder="Email" name="email"
                    value="{{ old('email') }}" required>
                  <input type="password" class="modal-input" placeholder="Password" name="password" required>
                  <input type="password" class="modal-input" placeholder="Confirm Password"
                    name="password_confirmation" required>
                  <button type="submit" class="modal-btn">Sign up</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Container -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
      <div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3" id="toastContainer"></div>
    </div>

    <!-- Main Content -->
    <main>
      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h5>KagayakuKin Yume Hotel</h5>
            <p>Experience luxury and comfort in the heart of the city.</p>
          </div>
          <div class="col-md-4">
            <h5>Quick Links</h5>
            <ul class="list-unstyled">
              <li><a href="{{ route('home') }}" class="text-light">Home</a></li>
              <li><a href="{{ route('rooms') }}" class="text-light">Rooms</a></li>
              <li><a href="{{ route('about') }}" class="text-light">About</a></li>
              <li><a href="{{ route('contact') }}" class="text-light">Contact</a></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h5>Contact Us</h5>
            <p>Email: info@kagayakukin.com<br>Phone: +1 234 567 890</p>
          </div>
        </div>
        <hr class="bg-light">
        <p class="text-center mb-0">&copy; 2025 KagayakuKin Yume Hotel. All rights reserved.</p>
      </div>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.layout.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const authModal = document.getElementById('authModal');
        const authBtn = document.getElementById('AuthBtn');
        const authSection = document.getElementById('authSection');
        const loginForm = document.getElementById('loginFormSubmit');
        const signupForm = document.getElementById('signupFormSubmit');
        const initialLogoutForm = document.getElementById('logoutForm');

        if (authModal && authBtn) {
          authBtn.addEventListener('click', () => {
            const form = authBtn.dataset.form;
            if (form === 'login') {
              document.getElementById('login-tab').click();
            } else if (form === 'signup') {
              document.getElementById('signup-tab').click();
            }
          });
        }

        function attachLogoutListener(form) {
          form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            try {
              const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': csrfToken,
                },
                body: new FormData(form),
              });
              const data = await response.json();

              if (data.status === 'success') {
                showToast('success', data.message);
                authSection.innerHTML = `
                  <a href="{{ route('booking') }}" class="btn btn-primary">Book now</a>
                  <button class="btn btn-outline-secondary" id="AuthBtn" data-bs-toggle="modal"
                    data-bs-target="#authModal" data-form="login">Sign Up / Login</button>
                `;
                // Update CSRF token in meta tag and forms
                if (data.csrf_token) {
                  document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                  document.querySelector('#loginFormSubmit input[name="_token"]').value = data.csrf_token;
                  document.querySelector('#signupFormSubmit input[name="_token"]').value = data.csrf_token;
                }
                // Reattach auth button listener
                const newAuthBtn = document.getElementById('AuthBtn');
                if (newAuthBtn) {
                  newAuthBtn.addEventListener('click', () => {
                    const form = newAuthBtn.dataset.form;
                    if (form === 'login') {
                      document.getElementById('login-tab').click();
                    } else if (form === 'signup') {
                      document.getElementById('signup-tab').click();
                    }
                  });
                }
              } else {
                showToast('error', data.message || 'Logout failed. Please try again.');
              }
            } catch (error) {
              showToast('error', 'An error occurred during logout. Please try again.');
              console.error('Logout Error:', error);
            }
          });
        }

        if (initialLogoutForm) {
          attachLogoutListener(initialLogoutForm);
        }

        if (loginForm) {
          loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearValidationErrors(loginForm);
            try {
              const response = await fetch(loginForm.action, {
                method: 'POST',
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                },
                body: new FormData(loginForm),
              });
              const data = await response.json();

              if (data.status === 'success') {
                showToast('success', data.message);
                const authModalInstance = bootstrap.Modal.getInstance(authModal);
                authModalInstance.hide();
                updateAuthSection(data.user);
                if (data.csrf_token) {
                  document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                  document.querySelector('#loginFormSubmit input[name="_token"]').value = data.csrf_token;
                  document.querySelector('#signupFormSubmit input[name="_token"]').value = data.csrf_token;
                }
              } else {
                showToast('error', data.message || 'Login failed. Please try again.');
                if (data.errors) {
                  displayValidationErrors(loginForm, data.errors);
                }
              }
            } catch (error) {
              showToast('error', 'An error occurred during login. Please try again.');
              console.error('Login Error:', error);
            }
          });
        }

        if (signupForm) {
          signupForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearValidationErrors(signupForm);
            try {
              const response = await fetch(signupForm.action, {
                method: 'POST',
                headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json',
                },
                body: new FormData(signupForm),
              });
              const data = await response.json();

              if (data.status === 'success') {
                showToast('success', data.message);
                const authModalInstance = bootstrap.Modal.getInstance(authModal);
                authModalInstance.hide();
                updateAuthSection(data.user);
                if (data.csrf_token) {
                  document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                  document.querySelector('#loginFormSubmit input[name="_token"]').value = data.csrf_token;
                  document.querySelector('#signupFormSubmit input[name="_token"]').value = data.csrf_token;
                }
              } else {
                showToast('error', data.message || 'Signup failed. Please try again.');
                if (data.errors) {
                  displayValidationErrors(signupForm, data.errors);
                }
              }
            } catch (error) {
              showToast('error', 'An error occurred during signup. Please try again.');
              console.error('Signup Error:', error);
            }
          });
        }

        function clearValidationErrors(form) {
          form.querySelectorAll('input').forEach(input => {
            input.classList.remove('is-invalid');
          });
        }

        function displayValidationErrors(form, errors) {
          Object.keys(errors).forEach(field => {
            const inputElement = form.querySelector(`[name="${field}"]`);
            if (inputElement) {
              inputElement.classList.add('is-invalid');
            }
          });
        }

        function updateAuthSection(user) {
          authSection.innerHTML = `
            <a href="{{ route('booking') }}" class="btn btn-primary">Book now</a>
            <div class="dropdown">
              <button class="btn btn-outline dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                ${user.Role === 'Admin' ? '[ADMIN]' : ''}
                ${user.Name ? user.Name + ' |' : ''}
                ${user.email}
              </button>
              <ul class="dropdown-menu" aria-labelledby="userDropdown">
                ${user.Role === 'Admin' ? '<li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>' : ''}
                <li>
                  <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <button type="submit" class="dropdown-item">Logout</button>
                  </form>
                </li>
              </ul>
            </div>
          `;
          const newLogoutForm = document.getElementById('logoutForm');
          if (newLogoutForm) {
            attachLogoutListener(newLogoutForm);
          }
        }

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
      });
    </script>
  </body>

</html>
