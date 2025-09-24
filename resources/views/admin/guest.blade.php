@extends('layouts.admin')
@section('title', 'Guest')
@section('content')
  <div id="dynamic-content">
    <h1 class="h2 main-text">Guest</h1>

    <div class="row">
      <div class="col-md-12">
        <!-- Search Form -->
        <div class="mb-3">
          <form id="search-form" action="{{ route('admin.guest') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2"
              placeholder="Search by reservation ID, user name, room type, size, or room number"
              value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-custom">Search</button>
            @if ($search)
              <a href="{{ route('admin.guest') }}" class="btn btn-secondary ms-2">Clear</a>
            @endif
          </form>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="guestTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'pending' ? 'active' : '' }}" id="pending-tab" data-tab="pending"
              type="button" role="tab" aria-selected="{{ $tab === 'pending' ? 'true' : 'false' }}">Pending
              Reservations</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'confirmed' ? 'active' : '' }}" id="confirmed-tab" data-tab="confirmed"
              type="button" role="tab" aria-selected="{{ $tab === 'confirmed' ? 'true' : 'false' }}">Confirmed
              Reservations</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'ongoing' ? 'active' : '' }}" id="ongoing-tab" data-tab="ongoing"
              type="button" role="tab" aria-selected="{{ $tab === 'ongoing' ? 'true' : 'false' }}">Ongoing
              Reservations</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'completed' ? 'active' : '' }}" id="completed-tab" data-tab="completed"
              type="button" role="tab" aria-selected="{{ $tab === 'completed' ? 'true' : 'false' }}">Completed
              Reservations</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'cancelled' ? 'active' : '' }}" id="cancelled-tab" data-tab="cancelled"
              type="button" role="tab" aria-selected="{{ $tab === 'cancelled' ? 'true' : 'false' }}">Cancelled
              Reservations</button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="guestTabContent">
          <div class="tab-pane fade show active" id="current-tab-content" role="tabpanel">
            @include("admin.partials.guest.tab_{$tab}")
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const tabs = document.querySelectorAll('#guestTabs .nav-link');
      const tabContent = document.getElementById('current-tab-content');
      const searchForm = document.getElementById('search-form');

      tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
          e.preventDefault();
          const activeTab = document.querySelector('.nav-link.active');
          activeTab.classList.remove('active');
          this.classList.add('active');
          loadTabContent(this.dataset.tab);
        });
      });

      document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href]');
        if (link && (link.closest('.pagination') || link.closest('thead'))) {
          e.preventDefault();
          const url = new URL(link.href);
          url.searchParams.set('tab', document.querySelector('.nav-link.active').dataset.tab);
          loadTabContent(null, url.toString());
        }
      });

      if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          const url = new URL(this.action);
          formData.forEach((value, key) => url.searchParams.set(key, value));
          url.searchParams.set('tab', document.querySelector('.nav-link.active').dataset.tab);
          loadTabContent(null, url.toString());
        });
      }

      function loadTabContent(tab, url = null) {
        if (!url) {
          url = "{{ route('admin.guest') }}?tab=" + tab;
          const search = searchForm.querySelector('input[name="search"]').value;
          if (search) url += "&search=" + encodeURIComponent(search);
        }
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
            }
          })
          .then(res => res.json())
          .then(data => {
            tabContent.innerHTML = data.html;
          })
          .catch(error => console.error('Error:', error));
      }
    });
  </script>
@endsection
