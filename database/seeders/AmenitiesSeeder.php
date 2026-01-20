<?php

namespace Database\Seeders;

use App\Models\Amenities;
use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'Swimming Pool', 'icon' => 'fas-swimming-pool', 'category' => 'recreation'],
            ['name' => 'Free WiFi', 'icon' => 'fas-wifi', 'category' => 'connectivity'],
            ['name' => 'Parking', 'icon' => 'fas-parking', 'category' => 'facilities'],
            ['name' => 'Restaurant', 'icon' => 'fas-utensils', 'category' => 'dining'],
            ['name' => 'Bar', 'icon' => 'fas-cocktail', 'category' => 'dining'],
            ['name' => 'Fitness Center', 'icon' => 'fas-dumbbell', 'category' => 'recreation'],
            ['name' => 'Spa', 'icon' => 'fas-spa', 'category' => 'wellness'],
            ['name' => 'Room Service', 'icon' => 'fas-concierge-bell', 'category' => 'service'],
            ['name' => '24-Hour Reception', 'icon' => 'fas-clock', 'category' => 'service'],
            ['name' => 'Air Conditioning', 'icon' => 'fas-snowflake', 'category' => 'room'],
            ['name' => 'TV', 'icon' => 'fas-tv', 'category' => 'room'],
            ['name' => 'Mini Bar', 'icon' => 'fas-wine-bottle', 'category' => 'room'],
            ['name' => 'Safe', 'icon' => 'fas-lock', 'category' => 'room'],
            ['name' => 'Business Center', 'icon' => 'fas-briefcase', 'category' => 'business'],
            ['name' => 'Conference Room', 'icon' => 'fas-users', 'category' => 'business'],
            ['name' => 'Laundry Service', 'icon' => 'fas-tshirt', 'category' => 'service'],
            ['name' => 'Airport Shuttle', 'icon' => 'fas-shuttle-van', 'category' => 'transport'],
            ['name' => 'Pet Friendly', 'icon' => 'fas-paw', 'category' => 'facilities'],
            ['name' => 'Beach Access', 'icon' => 'fas-umbrella-beach', 'category' => 'recreation'],
            ['name' => 'Golf Course', 'icon' => 'fas-golf-ball', 'category' => 'recreation'],
        ];

        foreach ($amenities as $amenity) {
            Amenities::create($amenity);
        }
    }
}
