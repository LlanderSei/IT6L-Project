<div class="card p-3 mb-3">
  <h4>Pending Reservations</h4>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'ID', 'direction' => $sort === 'ID' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Reservation ID
            @if ($sort === 'ID')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'UserName', 'direction' => $sort === 'UserName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            User Name
            @if ($sort === 'UserName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'RoomTypeName', 'direction' => $sort === 'RoomTypeName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Room Type
            @if ($sort === 'RoomTypeName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'RoomSizeName', 'direction' => $sort === 'RoomSizeName' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Room Size
            @if ($sort === 'RoomSizeName')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'HasServices', 'direction' => $sort === 'HasServices' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Has Services
            @if ($sort === 'HasServices')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'CheckInDate', 'direction' => $sort === 'CheckInDate' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Check-In Date
            @if ($sort === 'CheckInDate')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'CheckOutDate', 'direction' => $sort === 'CheckOutDate' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Check-Out Date
            @if ($sort === 'CheckOutDate')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'AmountPaid', 'direction' => $sort === 'AmountPaid' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Amount Paid
            @if ($sort === 'AmountPaid')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>
          <a
            href="{{ route('admin.guest', array_merge(request()->query(), ['sort' => 'TotalAmount', 'direction' => $sort === 'TotalAmount' && $direction === 'asc' ? 'desc' : 'asc', 'tab' => 'pending'])) }}">
            Total Amount
            @if ($sort === 'TotalAmount')
              <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
            @endif
          </a>
        </th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($reservations as $reservation)
        <tr>
          <td>{{ $reservation->ID }}</td>
          <td>{{ $reservation->UserName }}</td>
          <td>{{ $reservation->RoomTypeName }}</td>
          <td>{{ $reservation->RoomSizeName }}</td>
          <td>{{ $reservation->HasServices ? 'Yes' : 'No' }}</td>
          <td>{{ Carbon\Carbon::parse($reservation->CheckInDate)->format('Y-m-d') }}</td>
          <td>{{ Carbon\Carbon::parse($reservation->CheckOutDate)->format('Y-m-d') }}</td>
          <td>₱{{ number_format($reservation->AmountPaid, 2) }}</td>
          <td>₱{{ number_format($reservation->TotalAmount, 2) }}</td>
          <td>
            <a href="{{ route('admin.booking.review', $reservation->ID) }}" class="btn btn-sm btn-custom">Review
              Info</a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="10">No pending reservations found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div class="pagination-container">
    {{ $reservations->links() }}
  </div>
</div>
