<div class="card p-3 mb-3">
  <h4>Occupied Rooms</h4>
  <table class="table">
    <thead>
      <tr>
        <th class="col-md-2">
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'RoomName', 'direction' => $sort === 'RoomName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'occupied'])) }}">
            Room Number
            @if ($sort === 'RoomName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'status', 'direction' => $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'occupied'])) }}">
            Status
            @if ($sort === 'status')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'RoomTypeName', 'direction' => $sort === 'RoomTypeName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'occupied'])) }}">
            Room Type
            @if ($sort === 'RoomTypeName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'RoomSizeName', 'direction' => $sort === 'RoomSizeName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'occupied'])) }}">
            Room Size
            @if ($sort === 'RoomSizeName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'Floor', 'direction' => $sort === 'Floor' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'occupied'])) }}">
            Floor
            @if ($sort === 'Floor')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.rooms', array_merge(request()->query(), ['sort' => 'Occupant', 'direction' => $sort === 'Occupant' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'occupied'])) }}">
            Occupant
            @if ($sort === 'Occupant')
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
          <td>{{ $reservation->status }}</td>
          <td>{{ $reservation->RoomTypeName }}</td>
          <td>{{ $reservation->RoomSizeName }}</td>
          <td>{{ $reservation->Floor }}</td>
          <td>{{ $reservation->Occupant }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="6">No occupied rooms found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination-container">
    {{ $reservations->links() }}
  </div>
</div>
