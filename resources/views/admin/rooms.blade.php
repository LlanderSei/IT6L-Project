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
      </div>
    </div>
  </div>

  <!-- Modified: Enhanced error handling and tab parameter preservation -->
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
          const activeTab = document.querySelector('.nav-link.active')?.dataset.tab || 'occupied';
          url.searchParams.set('tab', activeTab);
          loadTabContent(null, url.toString());
        }
      });

      if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          const url = new URL(this.action);
          formData.forEach((value, key) => url.searchParams.set(key, value));
          const activeTab = document.querySelector('.nav-link.active')?.dataset.tab || 'occupied';
          url.searchParams.set('tab', activeTab);
          loadTabContent(null, url.toString());
        });
      }

      function loadTabContent(tab, url = null) {
        if (!url) {
          url = "{{ route('admin.rooms') }}?tab=" + (tab || 'occupied');
          const search = searchForm.querySelector('input[name="search"]').value;
          if (search) url += "&search=" + encodeURIComponent(search);
        }
        console.log('Loading tab content for URL:', url); // Debug: Log URL
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
            }
          })
          .then(res => {
            console.log('Response Status:', res.status); // Debug: Log status
            if (!res.ok) {
              throw new Error(`HTTP error! Status: ${res.status}`);
            }
            return res.json();
          })
          .then(data => {
            console.log('Response Data:', data); // Debug: Log response data
            if (data.html) {
              tabContent.innerHTML = data.html;
            } else {
              tabContent.innerHTML = '<div class="alert alert-danger">Invalid response. Please try again.</div>';
            }
          })
          .catch(error => {
            console.error('Tab Content Error:', error);
            tabContent.innerHTML =
              '<div class="alert alert-danger">Failed to load tab content. Please try again.</div>';
          });
      }
    });
  </script>
  <!-- End Modified -->
@endsection
