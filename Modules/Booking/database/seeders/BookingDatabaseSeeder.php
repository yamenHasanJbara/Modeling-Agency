<?php

namespace Modules\Booking\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Booking\Models\Booking;

class BookingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory(100)->create();
    }
}
