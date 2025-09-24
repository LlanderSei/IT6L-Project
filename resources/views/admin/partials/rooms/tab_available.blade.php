<div class="card p-3 mb-3">
  <h4>Available Rooms</h4>
  <table class="table">
    <thead>
      <tr>
        <th class="col-md-2">
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'RoomName', 'direction' => $sort === 'RoomName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'available'])) }}">
            Room Number
            @if ($sort === 'RoomName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'RoomTypeName', 'direction' => $sort === 'RoomTypeName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'available'])) }}">
            Room Type
            @if ($sort === 'RoomTypeName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'RoomSizeName', 'direction' => $sort === 'RoomSizeName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'available'])) }}">
            Room Size
            @if ($sort === 'RoomSizeName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'Floor', 'direction' => $sort === 'Floor' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'available'])) }}">
            Floor
            @if ($sort === 'Floor')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($reservations as $reservation)
        <tr>
          <td>{{ $reservation->RoomName }}</td>
          <td>{{ $reservation->RoomTypeName }}</td>
          <td>{{ $reservation->RoomSizeName }}</td>
          <td>{{ $reservation->Floor }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4">No available rooms found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination-container">
    {{ $reservations->links() }}
  </div>
</div>
