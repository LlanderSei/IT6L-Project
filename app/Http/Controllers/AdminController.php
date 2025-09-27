<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\AssignedRoom;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\PaymentInfos;
use Carbon\Carbon;

class AdminController extends Controller {
  public function viewDashboard() {
    return view('admin.dashboard');
  }

  public function getDashboardData(Request $request) {
    $today = Carbon::today();
    $period = $request->query('period', 'weekly');

    $checkInToday = Booking::whereDate('SettledCheckIn', $today->toDateString())->where('BookingStatus', 'Ongoing')->count();
    $checkOutToday = Booking::whereDate('SettledCheckOut', $today->toDateString())->where('BookingStatus', 'Ended')->count();
    $totalInHotel = AssignedRoom::where('Status', 'Ongoing')->count();
    $availableRooms = Room::count() - $totalInHotel;
    $occupiedRooms = $totalInHotel;
    $revenueToday = (float) PaymentInfos::whereDate('updated_at', $today->toDateString())->where('PaymentStatus', 'Verified')->sum('TotalAmount');
    $monthlyRevenue = (float) PaymentInfos::whereMonth('created_at', $today->month)
      ->whereYear('created_at', $today->year)
      ->where('PaymentStatus', 'Verified')
      ->sum('TotalAmount');
    $occupancyRate = (Room::count() > 0) ? ($occupiedRooms / Room::count() * 100) : 0;

    Log::debug('Dashboard Metrics:', [
      'today' => $today->month,
      'checkInToday' => $checkInToday,
      'checkOutToday' => $checkOutToday,
      'totalInHotel' => $totalInHotel,
      'availableRooms' => $availableRooms,
      'occupiedRooms' => $occupiedRooms,
      'revenueToday' => $revenueToday,
      'monthlyRevenue' => $monthlyRevenue,
      'occupancyRate' => $occupancyRate,
    ]);

    $metrics = [
      'checkIn' => $checkInToday,
      'checkOut' => $checkOutToday,
      'totalInHotel' => $totalInHotel,
      'availableRooms' => $availableRooms,
      'occupiedRooms' => $occupiedRooms,
      'revenueToday' => $revenueToday,
      'monthlyRevenue' => $monthlyRevenue,
      'occupancyRate' => $occupancyRate,
    ];

    $labels = [];
    $revenueData = [];
    $occupancyData = [];

    if ($period === 'weekly') {
      $start = $today->copy()->startOfWeek();
      $end = $today->copy()->endOfWeek();
      for ($date = $start; $date <= $end; $date = $date->addDay()) {
        $labels[] = $date->format('D');
        $revenueData[] = (float) PaymentInfos::whereDate('updated_at', $date)->where('PaymentStatus', 'Verified')->sum('TotalAmount');
        $occupancyData[] = (Room::count() > 0) ? (AssignedRoom::whereDate('created_at', $date)
          ->whereHas('booking', function ($query) use ($date) {
            $query->where('SettledCheckIn', '<=', $date)->orWhere('SettledCheckOut', '>=', $date);
          })->count() / Room::count() * 100) : 0;
        // $occupancyData[] = (Room::count() > 0) ? (AssignedRoom::where('Status', 'Ongoing')
        //   ->whereHas('booking', function ($query) use ($date) {
        //     $query->where('SettledCheckIn', '<=', $date)->where('SettledCheckOut', '>=', $date);
        //   })->count() / Room::count() * 100) : 0;
      }
    } elseif ($period === 'monthly') {
      $start = $today->copy()->startOfMonth();
      $end = $today->copy()->endOfMonth();
      $weekCount = 1;
      for ($date = $start; $date <= $end; $date = $date->addWeek()) {
        $labels[] = 'Week ' . $weekCount++;
        $revenueData[] = (float) PaymentInfos::whereBetween('updated_At', [$date, $date->copy()->addWeek()])->where('PaymentStatus', 'Verified')->sum('TotalAmount');
        $occupancyData[] = (Room::count() > 0) ? (AssignedRoom::whereHas('booking', function ($query) use ($date) {
          $query->where('SettledCheckIn', '<=', $date->copy()->addWeek())->orWhere('SettledCheckOut', '>=', $date);
        })->count() / Room::count() * 100) : 0;
      }
    } elseif ($period === 'yearly') {
      $start = $today->copy()->startOfYear();
      $end = $today->copy()->endOfYear();
      for ($month = 1; $month <= 12; $month++) {
        $labels[] = Carbon::createFromDate($today->year, $month, 1)->format('M');
        $revenueData[] = (float) PaymentInfos::whereMonth('updated_at', $month)
          ->whereYear('created_at', $today->year)
          ->where('PaymentStatus', 'Verified')
          ->sum('TotalAmount');
        $occupancyData[] = (Room::count() > 0) ? (AssignedRoom::whereHas('booking', function ($query) use ($today, $month) {
          $query->whereMonth('CheckInDate', $month)->whereYear('SettledCheckIn', $today->year);
        })->count() / Room::count() * 100) : 0;
      }
    }

    $chartData = [
      'labels' => $labels,
      'revenueData' => $revenueData,
      'occupancyData' => array_map(function ($rate) {
        return round($rate, 2);
      }, $occupancyData),
    ];

    Log::debug('getDashboardData Response:', ['metrics' => $metrics, 'chartData' => $chartData]);

    return response()->json([
      'metrics' => $metrics,
      'chartData' => $chartData,
    ]);
  }

  public function viewMasterDashboard() {
    return view('admin.master_dashboard');
  }

  public function viewGuest(Request $request) {
    $currentDate = Carbon::today();
    $search = $request->input('search');
    $sort = $request->input('sort', 'ID');
    $direction = $request->input('direction', 'asc');
    $perPage = 30;
    $tab = $request->input('tab', 'pending');

    $validSortColumns = ['ID', 'UserName', 'RoomTypeName', 'RoomSizeName', 'HasServices', 'CheckInDate', 'CheckOutDate', 'TotalAmount', 'RoomName'];
    if (!in_array($sort, $validSortColumns)) {
      $sort = 'ID';
    }

    $baseQuery = Booking::with(['roomType', 'roomSize', 'servicesAdded', 'costDetails', 'assignedRooms.room'])
      ->leftJoin('users', 'BookingDetails.UserID', '=', 'users.id')
      ->leftJoin('RoomTypes', 'BookingDetails.RoomTypeID', '=', 'RoomTypes.ID')
      ->leftJoin('RoomSizes', 'BookingDetails.RoomSizeID', '=', 'RoomSizes.ID')
      ->leftJoin('BookingCostDetails', 'BookingDetails.ID', '=', 'BookingCostDetails.BookingDetailID')
      ->leftJoin('AssignedRooms', 'BookingDetails.ID', '=', 'AssignedRooms.BookingDetailID')
      ->leftJoin('Rooms', 'AssignedRooms.RoomID', '=', 'Rooms.ID')
      ->leftJoin('PaymentInfos', 'BookingDetails.ID', '=', 'PaymentInfos.BookingDetailID')
      ->select(
        'BookingDetails.ID',
        'BookingDetails.CheckInDate',
        'BookingDetails.CheckOutDate',
        'BookingDetails.BookingStatus',
        'users.name as UserName',
        'RoomTypes.RoomTypeName',
        'RoomSizes.RoomSizeName',
        'BookingCostDetails.TotalAmount',
        DB::raw('IFNULL(Rooms.RoomName, "") as RoomName'),
        DB::raw('EXISTS (SELECT 1 FROM ServicesAdded WHERE ServicesAdded.BookingDetailID = BookingDetails.ID) as HasServices'),
        DB::raw('COALESCE(SUM(CASE WHEN PaymentInfos.PaymentStatus = "Verified" THEN PaymentInfos.TotalAmount ELSE 0 END), 0) as AmountPaid')
      )
      ->groupBy(
        'BookingDetails.ID',
        'BookingDetails.CheckInDate',
        'BookingDetails.CheckOutDate',
        'BookingDetails.BookingStatus',
        'users.name',
        'RoomTypes.RoomTypeName',
        'RoomSizes.RoomSizeName',
        'BookingCostDetails.TotalAmount',
        'Rooms.RoomName'
      );

    if ($search) {
      $baseQuery->where(function ($q) use ($search) {
        $q->where('BookingDetails.ID', 'like', '%' . $search . '%')
          ->orWhere('users.name', 'like', '%' . $search . '%')
          ->orWhere('RoomTypes.RoomTypeName', 'like', '%' . $search . '%')
          ->orWhere('RoomSizes.RoomSizeName', 'like', '%' . $search . '%')
          ->orWhere('Rooms.RoomName', 'like', '%' . $search . '%');
      });
    }

    if ($sort === 'UserName') {
      $baseQuery->orderBy('users.name', $direction);
    } elseif ($sort === 'RoomTypeName') {
      $baseQuery->orderBy('RoomTypes.RoomTypeName', $direction);
    } elseif ($sort === 'RoomSizeName') {
      $baseQuery->orderBy('RoomSizes.RoomSizeName', $direction);
    } elseif ($sort === 'HasServices') {
      $baseQuery->orderBy('HasServices', $direction);
    } elseif ($sort === 'RoomName') {
      $baseQuery->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', 1) AS UNSIGNED) $direction")
        ->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', -1) AS UNSIGNED) $direction");
    } else {
      $baseQuery->orderBy('BookingDetails.' . $sort, $direction);
    }

    $reservations = null;
    $pageName = 'page';

    switch ($tab) {
      case 'pending':
        $query = clone $baseQuery;
        $reservations = $query->where('BookingDetails.BookingStatus', 'Pending')
          ->whereExists(function ($q) {
            $q->select(DB::raw(1))
              ->from('PaymentInfos')
              ->whereColumn('PaymentInfos.BookingDetailID', 'BookingDetails.ID')
              ->whereIn('PaymentInfos.PaymentStatus', ['Submitted', 'Pending']);
          })
          ->paginate($perPage, ['*'], $pageName)
          ->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction, 'tab' => $tab]);
        break;
      case 'confirmed':
        $query = clone $baseQuery;
        $reservations = $query->where('BookingDetails.BookingStatus', 'Confirmed')
          ->paginate($perPage, ['*'], $pageName)
          ->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction, 'tab' => $tab]);
        break;
      case 'ongoing':
        $query = clone $baseQuery;
        $reservations = $query->where('BookingDetails.BookingStatus', 'Ongoing')
          ->paginate($perPage, ['*'], $pageName)
          ->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction, 'tab' => $tab]);
        break;
      case 'completed':
        $query = clone $baseQuery;
        $reservations = $query->where('BookingDetails.BookingStatus', 'Ended')
          ->paginate($perPage, ['*'], $pageName)
          ->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction, 'tab' => $tab]);
        break;
      case 'cancelled':
        $query = clone $baseQuery;
        $reservations = $query->where('BookingDetails.BookingStatus', 'Cancelled')
          ->paginate($perPage, ['*'], $pageName)
          ->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction, 'tab' => $tab]);
        break;
      default:
        abort(404);
    }

    if ($request->ajax()) {
      return response()->json([
        'html' => view("admin.partials.guest.tab_{$tab}", compact('reservations', 'search', 'sort', 'direction'))->render()
      ]);
    }

    return view('admin.guest', compact('reservations', 'search', 'sort', 'direction', 'tab'));
  }

  public function viewRooms(Request $request) {
    Log::debug('viewRooms Request:', ['query' => $request->query()]); // Debug: Log request parameters
    $search = $request->input('search');
    $sort = $request->input('sort', 'RoomName');
    $direction = $request->input('direction', 'asc');
    $perPage = 30;
    $tab = $request->input('tab', 'occupied');

    $validSortColumns = ['RoomName', 'status', 'RoomTypeName', 'RoomSizeName', 'Floor', 'Occupant'];
    if (!in_array($sort, $validSortColumns)) {
      $sort = 'RoomName';
    }

    $baseQuery = Room::with(['roomType', 'roomSize'])
      ->leftJoin('RoomTypes', 'Rooms.RoomTypeID', '=', 'RoomTypes.ID')
      ->leftJoin('RoomSizes', 'Rooms.RoomSizeID', '=', 'RoomSizes.ID')
      ->leftJoin('AssignedRooms', 'Rooms.ID', '=', 'AssignedRooms.RoomID')
      ->leftJoin('BookingDetails', 'AssignedRooms.BookingDetailID', '=', 'BookingDetails.ID')
      ->leftJoin('users', 'BookingDetails.UserID', '=', 'users.id')
      ->select(
        'Rooms.ID',
        'Rooms.RoomName',
        'Rooms.Floor',
        'RoomTypes.RoomTypeName as RoomTypeName', // Modified: Use lowercase 'name'
        'RoomSizes.RoomSizeName as RoomSizeName', // Modified: Use lowercase 'name'
        'AssignedRooms.Status as status',
        'users.Name as Occupant'
      );

    if ($search) {
      $baseQuery->where(function ($query) use ($search) {
        $query->where('Rooms.RoomName', 'like', "%$search%")
          ->orWhere('RoomTypes.name', 'like', "%$search%")
          ->orWhere('RoomSizes.name', 'like', "%$search%")
          ->orWhere('Rooms.Floor', 'like', "%$search%")
          ->orWhere('users.Name', 'like', "%$search%");
      });
    }

    if ($tab === 'occupied') {
      $baseQuery->whereNotNull('BookingDetails.ID')
        ->where('AssignedRooms.Status', 'Ongoing');
    } elseif ($tab === 'available') {
      $baseQuery->whereNull('BookingDetails.ID');
    }

    try {
      $reservations = $baseQuery->orderBy($sort, $direction)->paginate($perPage);
    } catch (\Exception $e) {
      Log::error('viewRooms Query Error:', ['error' => $e->getMessage(), 'sql' => $baseQuery->toSql()]);
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to load rooms data.',
        'error' => $e->getMessage()
      ], 500);
    }

    if ($request->ajax()) {
      return response()->json([
        'html' => view("admin.partials.rooms.tab_{$tab}", compact('reservations', 'sort', 'direction', 'search'))->render()
      ]);
    }

    return view('admin.rooms', compact('reservations', 'tab', 'sort', 'direction', 'search'));
  }

  public function viewFrontDesk() {
    return view('admin.frontdesk');
  }

  public function viewDeals() {
    return view('admin.deals');
  }

  public function viewRate() {
    return view('admin.rate');
  }

  public function viewBooking() {
    return view('admin.booking');
  }

  public function viewUserManagement(Request $request) {
    $search = $request->input('search');
    $sort = $request->input('sort', 'Name');
    $direction = $request->input('direction', 'asc');
    $perPage = 30;
    $tab = $request->input('tab', 'staff');

    $validSortColumns = ['Name', 'Role', 'email', 'created_at', 'updated_at'];
    if (!in_array($sort, $validSortColumns)) {
      $sort = 'Name';
    }

    $baseQuery = User::query();

    if ($search) {
      $baseQuery->where(function ($query) use ($search) {
        $query->where('Name', 'like', "%$search%")
          ->orWhere('email', 'like', "%$search%");
      });
    }

    if ($tab === 'staff') {
      $baseQuery->whereIn('Role', ['Admin', 'Manager']);
    } elseif ($tab === 'customers') {
      $baseQuery->where('Role', 'Customer');
    }

    $users = $baseQuery->orderBy($sort, $direction)->paginate($perPage);

    if ($request->ajax()) {
      return response()->json([
        'html' => view("admin.partials.usermanagement.tab_{$tab}", compact('users', 'sort', 'direction', 'search'))->render()
      ]);
    }

    return view('admin.usermanagement', compact('users', 'tab', 'sort', 'direction', 'search'));
  }

  public function addStaff(Request $request) {
    $validated = $request->validate([
      'Name' => 'required|string|max:255',
      'Role' => ['required', Rule::in(['Admin', 'Manager'])],
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
      'Name' => $validated['Name'],
      'Username' => strtolower(str_replace(' ', '_', $validated['Name'])),
      'Role' => $validated['Role'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'Staff added successfully!',
    ]);
  }

  public function updateUser(Request $request, $id) {
    // Debug: Log raw request content and parsed data
    Log::debug('updateUser Raw Input:', ['content' => $request->getContent()]);
    Log::debug('updateUser Parsed Data:', $request->all());

    $user = User::findOrFail($id);

    // Treat empty password as null
    if ($request->input('password') === '') {
      $request->merge(['password' => null]);
    }
    if ($request->input('password_confirmation') === '') {
      $request->merge(['password_confirmation' => null]);
    }

    try {
      $validated = $request->validate([
        'Name' => 'required|string|max:255',
        'Role' => ['required', Rule::in(['Admin', 'Manager'])],
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|min:6|confirmed',
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      Log::debug('updateUser Validation Errors:', $e->errors());
      throw $e;
    }

    $user->Name = $validated['Name'];
    $user->Role = $validated['Role'];
    $user->email = $validated['email'];
    if (!empty($validated['password'])) {
      $user->password = Hash::make($validated['password']);
    }
    $user->save();

    return response()->json([
      'status' => 'success',
      'message' => 'User updated successfully!',
    ]);
  }

  public function deleteUser($id) {
    if ($id == Auth::id()) {
      return response()->json([
        'status' => 'error',
        'message' => 'You cannot delete yourself!',
      ], 403);
    }

    $user = User::findOrFail($id);
    $user->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'User deleted successfully!',
    ]);
  }
}
