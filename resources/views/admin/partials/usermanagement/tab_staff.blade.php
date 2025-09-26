<div class="card p-3 mb-3">
  <div class="d-flex justify-content-between mb-3">
    <h4>Staffs</h4>
    <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add Staff</button>
  </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>
          <a
            href="{{ route('admin.usermanagement', array_merge(request()->query(), ['sort' => 'Name', 'direction' => $sort === 'Name' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'staff'])) }}">
            Name
            @if ($sort === 'Name')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.usermanagement', array_merge(request()->query(), ['sort' => 'Role', 'direction' => $sort === 'Role' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'staff'])) }}">
            Role
            @if ($sort === 'Role')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.usermanagement', array_merge(request()->query(), ['sort' => 'email', 'direction' => $sort === 'email' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'staff'])) }}">
            Email
            @if ($sort === 'email')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.usermanagement', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => $sort === 'created_at' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'staff'])) }}">
            Created At
            @if ($sort === 'created_at')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.usermanagement', array_merge(request()->query(), ['sort' => 'updated_at', 'direction' => $sort === 'updated_at' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'staff'])) }}">
            Updated At
            @if ($sort === 'updated_at')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($users as $user)
        <tr>
          <td>{{ $user->Name }}</td>
          <td>{{ $user->Role }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->created_at->format('Y-m-d H:i:s') ?? 'None' }}</td>
          <td>{{ $user->updated_at->format('Y-m-d H:i:s') ?? 'None' }}</td>
          <td>
            <!-- Modified: Changed data attributes to lowercase for consistency with JS dataset -->
            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $user->id }}"
              data-name="{{ $user->Name }}" data-role="{{ $user->Role }}" data-email="{{ $user->email }}"><i
                class="bi bi-pencil-square"></i></button>
            @if ($user->id != Auth::id())
              <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}"><i
                  class="bi bi-trash3"></i></button>
            @endif
            <!-- End Modified -->
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6">No staff found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination-container">
    {{ $users->links() }}
  </div>
</div>
