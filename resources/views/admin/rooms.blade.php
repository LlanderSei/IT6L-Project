@extends('layouts.admin')
@section('title', 'Rooms')
@section('content')
  <div id="dynamic-content">
    <h1 class="main-text">Rooms</h1>
    <div class="row">
      <div class="col-md-12">
        <!-- Search Form -->
        <div class="mb-3">
          <form id="search-form" action="{{ route('admin.rooms') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2"
              placeholder="Search by room number, type, size, floor, or user" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-custom">Search</button>
            @if ($search)
              <a href="{{ route('admin.rooms') }}" class="btn btn-secondary ms-2">Clear</a>
            @endif
          </form>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="roomsTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'occupied' ? 'active' : '' }}" id="occupied-tab" data-tab="occupied"
              type="button" role="tab" aria-selected="{{ $tab === 'occupied' ? 'true' : 'false' }}">Occupied
              Rooms</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'available' ? 'active' : '' }}" id="available-tab" data-tab="available"
              type="button" role="tab" aria-selected="{{ $tab === 'available' ? 'true' : 'false' }}">Available
              Rooms</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'all' ? 'active' : '' }}" id="all-tab" data-tab="all" type="button"
              role="tab" aria-selected="{{ $tab === 'all' ? 'true' : 'false' }}">All Rooms</button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="roomsTabContent">
          <div class="tab-pane fade show active" id="current-tab-content" role="tabpanel">
            @include("admin.partials.rooms.tab_{$tab}")
          </div>
        </div>

        <!-- Add Room Button (only for all tab, but shown always for simplicity) -->
        <div class="mb-3">
          <button class="btn btn-custom col-sm-12" data-bs-toggle="modal" data-bs-target="#addRoomModal">Add Room</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const tabs = document.querySelectorAll('#roomsTabs .nav-link');
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
          url = "{{ route('admin.rooms') }}?tab=" + tab;
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
