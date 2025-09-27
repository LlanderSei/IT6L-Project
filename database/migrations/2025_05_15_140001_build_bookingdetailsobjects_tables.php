<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('BookingDetails', function (Blueprint $table) {
      $table->id('ID');
      $table->foreignId('UserID')->constrained('Users', 'ID')->onDelete('cascade')->onUpdate('cascade');
      $table->foreignId('RoomTypeID')->constrained('RoomTypes', 'ID')->onDelete('cascade')->onUpdate('cascade');
      $table->dateTime('CheckInDate');
      $table->dateTime('CheckOutDate');
      $table->dateTime('SettledCheckIn')->nullable();
      $table->dateTime('SettledCheckOut')->nullable();
      $table->integer('NumberOfGuests');
      $table->foreignId('RoomSizeID')->constrained('RoomSizes', 'ID')->onDelete('cascade')->onUpdate('cascade');
      $table->enum('BookingStatus', ['Pending', 'Confirmed', 'Ongoing', 'Ended', 'Cancelled'])->default('Pending');
      $table->timestamps();
    });

    Schema::create('ServicesAdded', function (Blueprint $table) {
      $table->id('ID');
      $table->foreignId('BookingDetailID')->constrained('BookingDetails', 'ID')->onDelete('cascade')->onUpdate('cascade');
      $table->foreignId('ServiceID')->constrained('Services', 'ID')->onDelete('cascade')->onUpdate('cascade');
      $table->timestamps();
    });

    Schema::create('BookingCostDetails', function (Blueprint $table) {
      $table->id('ID');
      $table->foreignId('BookingDetailID')->constrained('BookingDetails', 'ID')->onDelete('cascade');
      $table->decimal('RoomBasePrice', 20, 2); // Base room price (RoomPrice)
      $table->decimal('RoomSucceedingNightsPrice', 20, 2)->default(0); // Succeeding nights cost
      $table->integer('Nights')->unsigned(); // Number of nights
      $table->decimal('GuestFee', 20, 2)->default(0); // PricePerPerson * NumberOfGuests (if applicable)
      $table->decimal('ServiceBasePrice', 20, 2)->default(0); // Sum of service prices for first day
      $table->decimal('ServiceSucceedingNightsPrice', 20, 2)->default(0); // Service prices for succeeding nights
      $table->decimal('Subtotal', 20, 2); // Total before discount
      $table->decimal('Discount', 20, 2)->default(0); // Loyalty discount amount
      $table->decimal('TotalAmount', 20, 2); // Final total after discount
      $table->timestamps();
    });

    Schema::create('AssignedRooms', function (Blueprint $table) {
      $table->id('ID');
      $table->foreignId('BookingDetailID')->constrained('BookingDetails', 'ID')->onDelete('cascade');
      $table->foreignId('RoomID')->constrained('Rooms', 'ID')->onDelete('cascade');
      $table->enum('Status', ['Ongoing', 'Ended']);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('BookingDetails');
    Schema::dropIfExists('ServicesAdded');
    Schema::dropIfExists('BookingCostDetails');
  }
};
