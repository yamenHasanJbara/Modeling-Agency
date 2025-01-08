<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Booking\Database\Seeders\BookingDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Model\Database\Seeders\ModelDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategoryDatabaseSeeder::class);
        $this->call(ModelDatabaseSeeder::class);
        $this->call(BookingDatabaseSeeder::class);
    }
}
