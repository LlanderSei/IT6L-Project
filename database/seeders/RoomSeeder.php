<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    for ($i = 1; $i <= 100; $i++) {
      for ($j = 1; $j <= 10; $j++) {
        DB::table('Rooms')->insertOrIgnore(
          [
            'RoomName' => str($i) . '-' . str($j),
            'RoomTypeID' => rand(1,3),
            'RoomSizeID' => rand(1,3),
            'Floor' => str($i),
          ]
        );
      }
    }
  }
}
