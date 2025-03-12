<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ComboStackDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);
    }
}