<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div class="container-fluid p-0">
      <div class="row">
        <nav class="col-md-2 col-12 sidebar">
          @include('admin.partials.sidebar')
        </nav>
        <main class="col-md-9 col-10 content ps-5">
          @yield('content')
        </main>
      </div>
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
      });
    </script>
    @yield('scripts')
  </body>

</html>
