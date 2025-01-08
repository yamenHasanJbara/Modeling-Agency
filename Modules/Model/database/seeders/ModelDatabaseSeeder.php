<?php

namespace Modules\Model\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Model\Models\Model;

class ModelDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::factory(100)->create();
    }
}
