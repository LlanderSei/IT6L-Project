<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model {
  protected $table = 'BookingDetails';
  protected $primaryKey = 'ID';
  protected $fillable = ['UserID', 'RoomTypeID', 'CheckInDate', 'CheckOutDate', 'SettledCheckIn', 'SettledCheckOut', 'NumberOfGuests', 'RoomSizeID', 'BookingStatus'];

  protected $casts = [
    'CheckInDate' => 'datetime',
    'CheckOutDate' => 'datetime',
  ];

  public function user() {
    return $this->belongsTo(User::class, 'UserID', 'id');
  }

  public function roomType() {
    return $this->belongsTo(RoomType::class, 'RoomTypeID', 'ID');
  }

  public function roomSize() {
    return $this->belongsTo(RoomSize::class, 'RoomSizeID', 'ID');
  }

  public function servicesAdded() {
    return $this->belongsToMany(Service::class, 'ServicesAdded', 'BookingDetailID', 'ServiceID');
  }

  public function paymentInfo() {
    return $this->hasOne(PaymentInfos::class, 'BookingDetailID', 'ID');
  }

  public function costDetails() {
    return $this->hasOne(BookingCostDetail::class, 'BookingDetailID', 'ID');
  }

  public function assignedRooms() {
    return $this->hasMany(AssignedRoom::class, 'BookingDetailID', 'ID');
  }
}
