<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\AmenitiesSeeder;
use Database\Seeders\HotelsSeeder;
use Database\Seeders\RoomsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AdminUserSeeder::class,
            AmenitiesSeeder::class,
            HotelsSeeder::class,
            RoomsSeeder::class,
        ]);
    }
}
