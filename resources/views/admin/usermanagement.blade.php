@extends('layouts.admin')
@section('title', 'User Management')
@section('content')
  <div id="dynamic-content">
    <h1 class="h2 main-text">User Management</h1>

    <div class="row">
      <div class="col-md-12">
        <!-- Search Form -->
        <div class="mb-3">
          <form id="search-form" action="{{ route('admin.usermanagement') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by name, username, or email"
              value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-custom">Search</button>
            @if ($search)
              <a href="{{ route('admin.usermanagement') }}" class="btn btn-secondary ms-2">Clear</a>
            @endif
          </form>
        </div>

        <!-- Add Staff Button (only for staff tab) -->
        @if ($tab === 'staff')
          <div class="mb-3">
            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add Staff</button>
          </div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="userTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'staff' ? 'active' : '' }}" id="staff-tab" data-tab="staff" type="button"
              role="tab" aria-selected="{{ $tab === 'staff' ? 'true' : 'false' }}">Staffs</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $tab === 'customers' ? 'active' : '' }}" id="customers-tab" data-tab="customers"
              type="button" role="tab"
              aria-selected="{{ $tab === 'customers' ? 'true' : 'false' }}">Customers</button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="userTabContent">
          <div class="tab-pane fade show active" id="current-tab-content" role="tabpanel">
            @include("admin.partials.usermanagement.tab_{$tab}")
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Staff Modal -->
  <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addStaffModalLabel">Add Staff</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addStaffForm" action="{{ route('admin.addStaff') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="Name" class="form-label">Name</label>
              <input type="text" class="form-control" id="Name" name="Name" required>
            </div>
            <div class="mb-3">
              <label for="Role" class="form-label">Role</label>
              <select class="form-select" id="Role" name="Role" required>
                <option value="Admin">Admin</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                required>
            </div>
            <button type="submit" class="btn btn-custom">Add Staff</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" id="edit_id" name="id">
            <div class="mb-3">
              <label for="edit_Name" class="form-label">Name</label>
              <input type="text" class="form-control" id="edit_Name" name="Name" required>
            </div>
            <div class="mb-3">
              <label for="edit_Role" class="form-label">Role</label>
              <select class="form-select" id="edit_Role" name="Role" required>
                <option value="Admin">Admin</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="edit_password" class="form-label">New Password (optional)</label>
              <input type="password" class="form-control" id="edit_password" name="password">
            </div>
            <div class="mb-3">
              <label for="edit_password_confirmation" class="form-label">Confirm New Password</label>
              <input type="password" class="form-control" id="edit_password_confirmation"
                name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-custom">Save Changes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this user?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const tabs = document.querySelectorAll('#userTabs .nav-link');
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

      // Handle Add Staff Form
      const addStaffForm = document.getElementById('addStaffForm');
      if (addStaffForm) {
        addStaffForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          fetch(this.action, {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              }
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
                loadTabContent('staff');
              } else {
                alert(data.message || 'Error adding staff.');
              }
            })
            .catch(error => console.error('Error:', error));
        });
      }

      // Handle Edit Button Click
      document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-btn')) {
          const user = e.target.dataset;
          document.getElementById('edit_id').value = user.id;
          document.getElementById('edit_Name').value = user.name;
          document.getElementById('edit_Role').value = user.role;
          document.getElementById('edit_email').value = user.email;
          document.getElementById('editForm').action = `/admin/users/${user.id}`;
          bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
        }
      });

      // Handle Edit Form Submit
      const editForm = document.getElementById('editForm');
      if (editForm) {
        editForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          fetch(this.action, {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              }
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                loadTabContent(document.querySelector('.nav-link.active').dataset.tab);
              } else {
                alert(data.message || 'Error updating user.');
              }
            })
            .catch(error => console.error('Error:', error));
        });
      }

      // Handle Delete Button Click
      let deleteId;
      document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-btn')) {
          deleteId = e.target.dataset.id;
          bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
        }
      });

      // Handle Confirm Delete
      const confirmDelete = document.getElementById('confirmDelete');
      if (confirmDelete) {
        confirmDelete.addEventListener('click', function() {
          fetch(`/admin/users/${deleteId}`, {
              method: 'DELETE',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              }
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                loadTabContent(document.querySelector('.nav-link.active').dataset.tab);
              } else {
                alert(data.message || 'Error deleting user.');
              }
            })
            .catch(error => console.error('Error:', error));
        });
      }

      function loadTabContent(tab, url = null) {
        if (!url) {
          url = "{{ route('admin.usermanagement') }}?tab=" + tab;
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
