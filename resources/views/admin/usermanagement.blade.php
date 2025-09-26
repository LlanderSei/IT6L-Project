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
              <div class="invalid-feedback" id="Name-error"></div>
            </div>
            <div class="mb-3">
              <label for="Role" class="form-label">Role</label>
              <select class="form-select" id="Role" name="Role" required>
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
              </select>
              <div class="invalid-feedback" id="Role-error"></div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
              <div class="invalid-feedback" id="email-error"></div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
              <div class="invalid-feedback" id="password-error"></div>
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                required>
              <div class="invalid-feedback" id="password_confirmation-error"></div>
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
              <div class="invalid-feedback" id="edit_Name-error"></div>
            </div>
            <div class="mb-3">
              <label for="edit_Role" class="form-label">Role</label>
              <select class="form-select" id="edit_Role" name="Role" required>
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
              </select>
              <div class="invalid-feedback" id="edit_Role-error"></div>
            </div>
            <div class="mb-3">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="edit_email" name="email" required>
              <div class="invalid-feedback" id="edit_email-error"></div>
            </div>
            <div class="mb-3">
              <label for="edit_password" class="form-label">New Password (optional)</label>
              <input type="password" class="form-control" id="edit_password" name="password">
              <div class="invalid-feedback" id="edit_password-error"></div>
            </div>
            <div class="mb-3">
              <label for="edit_password_confirmation" class="form-label">Confirm New Password</label>
              <input type="password" class="form-control" id="edit_password_confirmation"
                name="password_confirmation">
              <div class="invalid-feedback" id="edit_password_confirmation-error"></div>
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
        toastContainer.innerHTML += toastHTML;
        const toast = toastContainer.lastElementChild;
        new bootstrap.Toast(toast).show();
      }

      // Handle Add Staff Form
      const addStaffForm = document.getElementById('addStaffForm');
      if (addStaffForm) {
        addStaffForm.addEventListener('submit', async function(e) {
          e.preventDefault();
          // Clear previous errors
          document.querySelectorAll('#addStaffModal .invalid-feedback').forEach(el => el.textContent = '');
          document.querySelectorAll('#addStaffModal .form-control, #addStaffModal .form-select').forEach(el =>
            el.classList.remove('is-invalid'));
          const formData = new FormData(this);
          try {
            const response = await fetch(this.action, {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              }
            });
            const data = await response.json();
            if (data.status === 'success') {
              bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
              showToast('success', data.message);
              loadTabContent('staff');
            } else {
              if (response.status === 422 && data.errors) {
                Object.keys(data.errors).forEach(key => {
                  const errorElement = document.getElementById(`${key}-error`);
                  const inputElement = addStaffForm.querySelector(`[name="${key}"]`);
                  if (errorElement && inputElement) {
                    errorElement.textContent = data.errors[key][0];
                    inputElement.classList.add('is-invalid');
                  }
                });
              } else {
                showToast('error', data.message || 'Error adding staff.');
              }
            }
          } catch (error) {
            console.error('Add Staff AJAX Error:', error);
            showToast('error', 'An error occurred while adding staff. Please try again.');
          }
        });
      }

      // Clear validation on modal show
      const addStaffModal = document.getElementById('addStaffModal');
      if (addStaffModal) {
        addStaffModal.addEventListener('show.bs.modal', function() {
          document.querySelectorAll('#addStaffModal .invalid-feedback').forEach(el => el.textContent = '');
          document.querySelectorAll('#addStaffModal .form-control, #addStaffModal .form-select').forEach(el => el
            .classList.remove('is-invalid'));
          addStaffForm.reset();
        });
      }

      const editModal = document.getElementById('editModal');
      if (editModal) {
        editModal.addEventListener('show.bs.modal', function() {
          document.querySelectorAll('#editModal .invalid-feedback').forEach(el => el.textContent = '');
          document.querySelectorAll('#editModal .form-control, #editModal .form-select').forEach(el => el
            .classList.remove('is-invalid'));
        });
      }

      // Handle Edit Button Click
      document.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-btn');
        if (btn) {
          const user = btn.dataset;
          console.log('Edit Button Dataset:', user);
          document.getElementById('edit_id').value = user.id || '';
          document.getElementById('edit_Name').value = user.name || '';
          document.getElementById('edit_Role').value = user.role || 'Admin';
          document.getElementById('edit_email').value = user.email || '';
          document.getElementById('edit_password').value = '';
          document.getElementById('edit_password_confirmation').value = '';
          document.getElementById('editForm').action = `/admin/users/${user.id}`;
          bootstrap.Modal.getOrCreateInstance(document.getElementById('editModal')).show();
        }
      });

      // Handle Edit Form Submit
      const editForm = document.getElementById('editForm');
      if (editForm) {
        editForm.addEventListener('submit', async function(e) {
          e.preventDefault();
          // Clear previous errors
          document.querySelectorAll('#editModal .invalid-feedback').forEach(el => el.textContent = '');
          document.querySelectorAll('#editModal .form-control, #editModal .form-select').forEach(el => el
            .classList.remove('is-invalid'));

          // Create JSON payload
          const formData = new FormData(this);
          const jsonData = {
            _token: formData.get('_token'),
            _method: 'PATCH',
            id: formData.get('id'),
            Name: formData.get('Name'),
            Role: formData.get('Role'),
            email: formData.get('email'),
          };
          if (formData.get('password')) {
            jsonData.password = formData.get('password');
            jsonData.password_confirmation = formData.get('password_confirmation');
          }

          // Debug: Log JSON payload
          console.log('JSON Payload:', jsonData);

          let currentTab = document.querySelector('.nav-link.active')?.dataset.tab;
          if (currentTab === 'undefined' || !currentTab) {
            currentTab = 'staff';
          }

          try {
            const response = await fetch(this.action, {
              method: 'POST', // Use POST with _method: PATCH
              body: JSON.stringify(jsonData),
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              }
            });

            console.log('Response Status:', response.status);
            console.log('Response Headers:', [...response.headers.entries()]);

            const data = await response.json();
            console.log('Response Data:', data);

            if (response.ok && data.status === 'success') {
              bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
              showToast('success', data.message);
              loadTabContent(currentTab);
            } else {
              if (response.status === 422 && data.errors) {
                let errorMessages = [];
                Object.keys(data.errors).forEach(key => {
                  const errorElement = document.getElementById(`edit_${key}-error`);
                  const inputElement = editForm.querySelector(`[name="${key}"]`);
                  if (errorElement && inputElement) {
                    errorElement.textContent = data.errors[key][0];
                    inputElement.classList.add('is-invalid');
                    errorMessages.push(data.errors[key][0]);
                  } else {
                    console.log(`No error element or input found for key: ${key}`);
                  }
                });
                showToast('error', errorMessages.join(' ') || 'Validation failed. Please check the form.');
              } else if (response.status === 419) {
                showToast('error', 'CSRF token mismatch. Please refresh the page and try again.');
              } else {
                showToast('error', data.message || 'Error updating user.');
              }
            }
          } catch (error) {
            console.error('Edit Form AJAX Error:', error);
            showToast('error', 'An error occurred while updating user. Please try again.');
          }
        });
      }

      // Handle Delete Button Click
      let deleteId;
      document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-btn');
        if (btn) {
          deleteId = btn.dataset.id;
          bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
        }
      });

      // Handle Confirm Delete
      const confirmDelete = document.getElementById('confirmDelete');
      if (confirmDelete) {
        confirmDelete.addEventListener('click', async function() {
          let currentTab = document.querySelector('.nav-link.active')?.dataset.tab;
          if (currentTab === 'undefined' || !currentTab) {
            currentTab = 'staff';
          }

          try {
            const response = await fetch(`/admin/users/${deleteId}`, {
              method: 'DELETE',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              }
            });
            const data = await response.json();
            if (data.status === 'success') {
              bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
              showToast('success', data.message);
              loadTabContent(currentTab);
            } else {
              showToast('error', data.message || 'Error deleting user.');
            }
          } catch (error) {
            console.error('Delete AJAX Error:', error);
            showToast('error', 'An error occurred while deleting user. Please try again.');
          }
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
