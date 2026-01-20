<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Amenities;
use Illuminate\Database\Seeder;

class HotelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = [
            [
                'name' => 'Yes Hotel Boutique Rome',
                'description' => 'Yes Hotel Boutique Rome menawarkan kamar ber-AC dengan lounge, WiFi gratis, bar, resepsionis 24 jam, layanan transfer bandara, dan penyewaan sepeda. Semua kamar memiliki kamar mandi pribadi. Hotel ini terletak dekat Stasiun Termini, Metro Termini, dan Universitas La Sapienza.',
                'address' => 'Via Magenta 15, Central Station',
                'city' => 'Rome',
                'country' => 'Italy',
                'latitude' => 41.90100000,
                'longitude' => 12.50100000,
                'rating' => 5.0,
                'category' => 'budget',
                'distance_from_center' => 10.0,
                'amenities' => [1, 2, 3, 9, 10, 11], // Swimming Pool, WiFi, Parking, 24-Hour Reception, AC, TV
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&h=600&fit=crop',
                ]
            ],
            [
                'name' => 'Grand Luxury Hotel Paris',
                'description' => 'Hotel mewah dengan pemandangan menakjubkan ke Menara Eiffel. Menawarkan spa kelas dunia, restoran bintang Michelin, dan layanan concierge 24 jam.',
                'address' => '123 Champs-Élysées',
                'city' => 'Paris',
                'country' => 'France',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'rating' => 4.8,
                'category' => 'luxury',
                'distance_from_center' => 2.5,
                'amenities' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], // Most amenities
                'images' => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop',
                ]
            ],
            [
                'name' => 'Business Tower Hotel',
                'description' => 'Hotel bisnis modern di pusat kota dengan fasilitas meeting lengkap, business center, dan akses mudah ke bandara.',
                'address' => '456 Business District',
                'city' => 'London',
                'country' => 'United Kingdom',
                'latitude' => 51.5074,
                'longitude' => -0.1278,
                'rating' => 4.5,
                'category' => 'business',
                'distance_from_center' => 5.0,
                'amenities' => [2, 3, 6, 9, 10, 11, 14, 15, 16], // WiFi, Parking, Fitness, 24-Hour Reception, AC, TV, Business Center, Conference Room, Laundry
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&h=600&fit=crop',
                ]
            ],
            [
                'name' => 'Tropical Beach Resort',
                'description' => 'Resort tepi pantai dengan akses langsung ke pantai pribadi, kolam renang infinity, spa, dan berbagai aktivitas air.',
                'address' => '789 Beach Road',
                'city' => 'Bali',
                'country' => 'Indonesia',
                'latitude' => -8.4095,
                'longitude' => 115.1889,
                'rating' => 4.7,
                'category' => 'resort',
                'distance_from_center' => 15.0,
                'amenities' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 19], // Pool, WiFi, Parking, Restaurant, Bar, Fitness, Spa, Room Service, 24-Hour, AC, TV, Mini Bar, Beach Access
                'images' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&h=600&fit=crop',
                ]
            ],
            [
                'name' => 'City Budget Inn',
                'description' => 'Hotel budget-friendly dengan lokasi strategis di pusat kota. Menawarkan kamar bersih dan nyaman dengan harga terjangkau.',
                'address' => '321 Main Street',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'rating' => 3.5,
                'category' => 'budget',
                'distance_from_center' => 8.0,
                'amenities' => [2, 9, 10, 11], // WiFi, 24-Hour Reception, AC, TV
                'images' => [
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&h=600&fit=crop',
                ]
            ],
            [
                'name' => 'Executive Business Hotel',
                'description' => 'Hotel bisnis premium dengan fasilitas meeting lengkap, executive lounge, dan layanan bisnis profesional.',
                'address' => '654 Corporate Avenue',
                'city' => 'Singapore',
                'country' => 'Singapore',
                'latitude' => 1.3521,
                'longitude' => 103.8198,
                'rating' => 4.6,
                'category' => 'business',
                'distance_from_center' => 3.0,
                'amenities' => [2, 3, 6, 9, 10, 11, 12, 13, 14, 15, 16, 17], // WiFi, Parking, Fitness, 24-Hour, AC, TV, Mini Bar, Safe, Business Center, Conference, Laundry, Airport Shuttle
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&h=600&fit=crop',
                ]
            ],
            [
                'name' => 'Royal Palace Hotel',
                'description' => 'Hotel mewah bergaya istana dengan arsitektur klasik, suite mewah, restoran fine dining, dan layanan butler pribadi.',
                'address' => '987 Royal Boulevard',
                'city' => 'Dubai',
                'country' => 'United Arab Emirates',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
                'rating' => 4.9,
                'category' => 'luxury',
                'distance_from_center' => 1.5,
                'amenities' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17], // All amenities
                'images' => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&h=600&fit=crop',
                ]
            ],
            [
                'name' => 'Mountain View Resort',
                'description' => 'Resort pegunungan dengan pemandangan alam yang menakjubkan, hiking trails, spa alami, dan aktivitas outdoor.',
                'address' => '147 Mountain Road',
                'city' => 'Yogyakarta',
                'country' => 'Indonesia',
                'latitude' => -7.7956,
                'longitude' => 110.3695,
                'rating' => 4.4,
                'category' => 'resort',
                'distance_from_center' => 20.0,
                'amenities' => [1, 2, 3, 4, 6, 7, 9, 10, 11], // Pool, WiFi, Parking, Restaurant, Fitness, Spa, 24-Hour, AC, TV
                'images' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800&h=600&fit=crop',
                ]
            ],
        ];

        foreach ($hotels as $hotelData) {
            $amenitiesIds = $hotelData['amenities'];
            $images = $hotelData['images'] ?? [];
            unset($hotelData['amenities']);
            unset($hotelData['images']);

            $hotel = Hotel::create($hotelData);
            
            // Attach amenities to hotel
            $hotel->amenities()->attach($amenitiesIds);

            // Add images to hotel using Spatie Media Library
            foreach ($images as $imageUrl) {
                try {
                    $hotel->addMediaFromUrl($imageUrl)
                        ->toMediaCollection('hotel_images');
                } catch (\Exception $e) {
                    // Skip if image download fails
                    continue;
                }
            }
        }
    }
}
