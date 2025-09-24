<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\AssignedRoom;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller {
  public function viewDashboard() {
    return view('admin.dashboard');
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
        \DB::raw('IFNULL(Rooms.RoomName, "") as RoomName'),
        \DB::raw('EXISTS (SELECT 1 FROM ServicesAdded WHERE ServicesAdded.BookingDetailID = BookingDetails.ID) as HasServices'),
        \DB::raw('COALESCE(SUM(CASE WHEN PaymentInfos.PaymentStatus = "Verified" THEN PaymentInfos.TotalAmount ELSE 0 END), 0) as AmountPaid')
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
            $q->select(\DB::raw(1))
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
        $reservations = $query->where('BookingDetails.BookingStatus', 'Completed')
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
    $currentDate = Carbon::today();
    $search = $request->input('search');
    $sort = $request->input('sort', 'RoomName');
    $direction = $request->input('direction', 'asc');
    $perPage = 30;
    $tab = $request->input('tab', 'occupied');

    $validSortColumns = ['RoomName', 'RoomTypeName', 'RoomSizeName', 'Floor', 'status', 'Occupant'];
    if (!in_array($sort, $validSortColumns)) {
      $sort = 'RoomName';
    }

    $reservations = null;
    $pageName = 'page';

    switch ($tab) {
      case 'occupied':
        $query = AssignedRoom::join('Rooms', 'AssignedRooms.RoomID', '=', 'Rooms.ID')
          ->join('BookingDetails', 'AssignedRooms.BookingDetailID', '=', 'BookingDetails.ID')
          ->join('RoomTypes', 'Rooms.RoomTypeID', '=', 'RoomTypes.ID')
          ->join('RoomSizes', 'Rooms.RoomSizeID', '=', 'RoomSizes.ID')
          ->leftJoin('users', 'BookingDetails.UserID', '=', 'users.id')
          ->select(
            'Rooms.ID',
            'Rooms.RoomName',
            'Rooms.Floor',
            'RoomTypes.RoomTypeName',
            'RoomSizes.RoomSizeName',
            \DB::raw('IFNULL(users.name, "") as Occupant'),
            \DB::raw('CASE BookingDetails.BookingStatus
                        WHEN "Confirmed" THEN "Pending"
                        WHEN "Ongoing" THEN "Occupied"
                        ELSE "Unknown"
                      END as status')
          )
          ->whereIn('BookingDetails.BookingStatus', ['Confirmed', 'Ongoing'])
          ->where('BookingDetails.CheckInDate', '<=', $currentDate->endOfDay())
          ->where('BookingDetails.CheckOutDate', '>=', $currentDate->startOfDay());

        if ($search) {
          $query->where(function ($q) use ($search) {
            $q->where('Rooms.RoomName', 'like', '%' . $search . '%')
              ->orWhere('RoomTypes.RoomTypeName', 'like', '%' . $search . '%')
              ->orWhere('RoomSizes.RoomSizeName', 'like', '%' . $search . '%')
              ->orWhere('Rooms.Floor', 'like', '%' . $search . '%')
              ->orWhere('users.name', 'like', '%' . $search . '%');
          });
        }

        if ($sort === 'RoomName') {
          $query->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', 1) AS UNSIGNED) $direction")
            ->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', -1) AS UNSIGNED) $direction");
        } elseif ($sort === 'RoomTypeName') {
          $query->orderBy('RoomTypes.RoomTypeName', $direction);
        } elseif ($sort === 'RoomSizeName') {
          $query->orderBy('RoomSizes.RoomSizeName', $direction);
        } elseif ($sort === 'Floor') {
          $query->orderBy('Rooms.Floor', $direction);
        } elseif ($sort === 'status') {
          $query->orderBy('status', $direction);
        } elseif ($sort === 'Occupant') {
          $query->orderBy('Occupant', $direction);
        }

        $reservations = $query->groupBy(
          'Rooms.ID',
          'Rooms.RoomName',
          'Rooms.Floor',
          'RoomTypes.RoomTypeName',
          'RoomSizes.RoomSizeName',
          'users.name',
          'BookingDetails.BookingStatus'
        )->paginate($perPage, ['*'], $pageName)->appends([
          'search' => $search,
          'sort' => $sort,
          'direction' => $direction,
          'tab' => $tab,
        ]);
        break;
      case 'available':
        $query = Room::join('RoomTypes', 'Rooms.RoomTypeID', '=', 'RoomTypes.ID')
          ->join('RoomSizes', 'Rooms.RoomSizeID', '=', 'RoomSizes.ID')
          ->leftJoin('AssignedRooms', 'Rooms.ID', '=', 'AssignedRooms.RoomID')
          ->leftJoin('BookingDetails', 'AssignedRooms.BookingDetailID', '=', 'BookingDetails.ID')
          ->leftJoin('users', 'BookingDetails.UserID', '=', 'users.id')
          ->select(
            'Rooms.ID',
            'Rooms.RoomName',
            'Rooms.Floor',
            'RoomTypes.RoomTypeName',
            'RoomSizes.RoomSizeName',
            \DB::raw('IFNULL(users.name, "") as Occupant'),
            \DB::raw('"Available" as status')
          )
          ->whereNotIn('Rooms.ID', function ($q) use ($currentDate) {
            $q->select('RoomID')
              ->from('AssignedRooms')
              ->join('BookingDetails', 'AssignedRooms.BookingDetailID', '=', 'BookingDetails.ID')
              ->whereIn('BookingDetails.BookingStatus', ['Confirmed', 'Ongoing'])
              ->where('BookingDetails.CheckInDate', '<=', $currentDate->endOfDay())
              ->where('BookingDetails.CheckOutDate', '>=', $currentDate->startOfDay());
          });

        if ($search) {
          $query->where(function ($q) use ($search) {
            $q->where('Rooms.RoomName', 'like', '%' . $search . '%')
              ->orWhere('RoomTypes.RoomTypeName', 'like', '%' . $search . '%')
              ->orWhere('RoomSizes.RoomSizeName', 'like', '%' . $search . '%')
              ->orWhere('Rooms.Floor', 'like', '%' . $search . '%')
              ->orWhere('users.name', 'like', '%' . $search . '%');
          });
        }

        if ($sort === 'RoomName') {
          $query->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', 1) AS UNSIGNED) $direction")
            ->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', -1) AS UNSIGNED) $direction");
        } elseif ($sort === 'RoomTypeName') {
          $query->orderBy('RoomTypes.RoomTypeName', $direction);
        } elseif ($sort === 'RoomSizeName') {
          $query->orderBy('RoomSizes.RoomSizeName', $direction);
        } elseif ($sort === 'Floor') {
          $query->orderBy('Rooms.Floor', $direction);
        } elseif ($sort === 'status') {
          $query->orderBy('status', $direction);
        } elseif ($sort === 'Occupant') {
          $query->orderBy('Occupant', $direction);
        }

        $reservations = $query->groupBy(
          'Rooms.ID',
          'Rooms.RoomName',
          'Rooms.Floor',
          'RoomTypes.RoomTypeName',
          'RoomSizes.RoomSizeName',
          'users.name'
        )->paginate($perPage, ['*'], $pageName)->appends([
          'search' => $search,
          'sort' => $sort,
          'direction' => $direction,
          'tab' => $tab,
        ]);
        break;
      case 'all':
        $query = Room::join('RoomTypes', 'Rooms.RoomTypeID', '=', 'RoomTypes.ID')
          ->join('RoomSizes', 'Rooms.RoomSizeID', '=', 'RoomSizes.ID')
          ->leftJoin('AssignedRooms', 'Rooms.ID', '=', 'AssignedRooms.RoomID')
          ->leftJoin('BookingDetails', 'AssignedRooms.BookingDetailID', '=', 'BookingDetails.ID')
          ->leftJoin('users', 'BookingDetails.UserID', '=', 'users.id')
          ->select(
            'Rooms.ID',
            'Rooms.RoomName',
            'Rooms.Floor',
            'RoomTypes.RoomTypeName',
            'RoomSizes.RoomSizeName',
            \DB::raw('IFNULL(users.name, "") as Occupant'),
            \DB::raw('CASE
                        WHEN BookingDetails.BookingStatus = "Confirmed" THEN "Pending"
                        WHEN BookingDetails.BookingStatus = "Ongoing" THEN "Occupied"
                        ELSE "Available"
                      END as status')
          );

        if ($search) {
          $query->where(function ($q) use ($search) {
            $q->where('Rooms.RoomName', 'like', '%' . $search . '%')
              ->orWhere('RoomTypes.RoomTypeName', 'like', '%' . $search . '%')
              ->orWhere('RoomSizes.RoomSizeName', 'like', '%' . $search . '%')
              ->orWhere('Rooms.Floor', 'like', '%' . $search . '%')
              ->orWhere('users.name', 'like', '%' . $search . '%');
          });
        }

        if ($sort === 'RoomName') {
          $query->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', 1) AS UNSIGNED) $direction")
            ->orderByRaw("CAST(SUBSTRING_INDEX(Rooms.RoomName, '-', -1) AS UNSIGNED) $direction");
        } elseif ($sort === 'RoomTypeName') {
          $query->orderBy('RoomTypes.RoomTypeName', $direction);
        } elseif ($sort === 'RoomSizeName') {
          $query->orderBy('RoomSizes.RoomSizeName', $direction);
        } elseif ($sort === 'Floor') {
          $query->orderBy('Rooms.Floor', $direction);
        } elseif ($sort === 'status') {
          $query->orderBy('status', $direction);
        } elseif ($sort === 'Occupant') {
          $query->orderBy('Occupant', $direction);
        }

        $reservations = $query->groupBy(
          'Rooms.ID',
          'Rooms.RoomName',
          'Rooms.Floor',
          'RoomTypes.RoomTypeName',
          'RoomSizes.RoomSizeName',
          'users.name',
          'BookingDetails.BookingStatus'
        )->paginate($perPage, ['*'], $pageName)->appends([
          'search' => $search,
          'sort' => $sort,
          'direction' => $direction,
          'tab' => $tab,
        ]);
        break;
      default:
        abort(404);
    }

    if ($request->ajax()) {
      return response()->json([
        'html' => view("admin.partials.rooms.tab_{$tab}", compact('reservations', 'search', 'sort', 'direction'))->render()
      ]);
    }

    return view('admin.rooms', compact('reservations', 'search', 'sort', 'direction', 'tab'));
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

    $query = User::query();

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('Name', 'like', '%' . $search . '%')
          ->orWhere('Username', 'like', '%' . $search . '%')
          ->orWhere('email', 'like', '%' . $search . '%');
      });
    }

    if ($tab === 'staff') {
      $query->where('Role', '!=', 'Customer');
    } elseif ($tab === 'customers') {
      $query->where('Role', 'Customer');
    } else {
      abort(404);
    }

    $query->orderBy($sort, $direction);

    $users = $query->paginate($perPage)->appends([
      'search' => $search,
      'sort' => $sort,
      'direction' => $direction,
      'tab' => $tab,
    ]);

    if ($request->ajax()) {
      return response()->json([
        'html' => view("admin.partials.usermanagement.tab_{$tab}", compact('users', 'search', 'sort', 'direction'))->render()
      ]);
    }
    return view('admin.usermanagement', compact('users', 'search', 'sort', 'direction', 'tab'));
  }


  public function addStaff(Request $request) {
    $validated = $request->validate([
      'Name' => 'required|string|max:255',
      'Role' => ['required', Rule::in(['Admin'])],
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
    $user = User::findOrFail($id);

    $validated = $request->validate([
      'Name' => 'required|string|max:255',
      'Role' => ['required', Rule::in(['Admin', 'Customer'])],
      'email' => 'required|email|unique:users,email,' . $id,
      'password' => 'nullable|min:6|confirmed',
    ]);

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
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'User deleted successfully!',
    ]);
  }
}
