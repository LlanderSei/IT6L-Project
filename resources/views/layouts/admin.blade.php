<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome for sort icons (assumed to be included in admin_layout) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('layouts.styles')
    @yield('styles')
  </head>

  <body>
    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 col-12 sidebar">
          @include('admin.partials.sidebar')
        </nav>
        <main class="col-8 content p-3">
          @yield('content')
        </main>
      </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
          if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
          }
        });

        // Initialize toasts
        const toastContainer = document.getElementById('toastContainer');
        if (toastContainer) {
          const toasts = toastContainer.querySelectorAll('.toast');
          toasts.forEach(toast => {
            new bootstrap.Toast(toast).show();
          });
        }
      });
    </script>
    @yield('scripts')
  </body>

</html>
