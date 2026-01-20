<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Rooms;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            // Determine room types based on hotel category
            $roomTypes = $this->getRoomTypesForCategory($hotel->category);
            
            foreach ($roomTypes as $roomType) {
                Rooms::create([
                    'hotel_id' => $hotel->id,
                    'room_type' => $roomType['type'],
                    'description' => $roomType['description'],
                    'capacity' => $roomType['capacity'],
                    'max_adults' => $roomType['max_adults'],
                    'max_children' => $roomType['max_children'],
                    'base_price' => $roomType['base_price'],
                    'size_sqm' => $roomType['size_sqm'],
                    'bed_type' => $roomType['bed_type'],
                    'quantity_available' => $roomType['quantity'],
                ]);
            }
        }
    }

    private function getRoomTypesForCategory(string $category): array
    {
        return match($category) {
            'luxury' => [
                [
                    'type' => 'Presidential Suite',
                    'description' => 'Suite mewah dengan ruang tamu terpisah, kamar tidur master, dan balkon pribadi dengan pemandangan kota.',
                    'capacity' => 4,
                    'max_adults' => 4,
                    'max_children' => 2,
                    'base_price' => 5000000,
                    'size_sqm' => 120,
                    'bed_type' => 'King Size',
                    'quantity' => 2,
                ],
                [
                    'type' => 'Deluxe Suite',
                    'description' => 'Suite luas dengan fasilitas lengkap dan pemandangan indah.',
                    'capacity' => 3,
                    'max_adults' => 3,
                    'max_children' => 1,
                    'base_price' => 3500000,
                    'size_sqm' => 80,
                    'bed_type' => 'King Size',
                    'quantity' => 5,
                ],
                [
                    'type' => 'Superior Room',
                    'description' => 'Kamar superior dengan dekorasi elegan dan fasilitas premium.',
                    'capacity' => 2,
                    'max_adults' => 2,
                    'max_children' => 1,
                    'base_price' => 2000000,
                    'size_sqm' => 45,
                    'bed_type' => 'Queen Size',
                    'quantity' => 10,
                ],
            ],
            'business' => [
                [
                    'type' => 'Executive Suite',
                    'description' => 'Suite eksekutif dengan ruang kerja terpisah dan fasilitas bisnis lengkap.',
                    'capacity' => 2,
                    'max_adults' => 2,
                    'max_children' => 0,
                    'base_price' => 2500000,
                    'size_sqm' => 60,
                    'bed_type' => 'King Size',
                    'quantity' => 4,
                ],
                [
                    'type' => 'Business Room',
                    'description' => 'Kamar bisnis dengan meja kerja dan akses internet cepat.',
                    'capacity' => 2,
                    'max_adults' => 2,
                    'max_children' => 0,
                    'base_price' => 1500000,
                    'size_sqm' => 35,
                    'bed_type' => 'Double',
                    'quantity' => 15,
                ],
                [
                    'type' => 'Standard Room',
                    'description' => 'Kamar standar yang nyaman untuk perjalanan bisnis.',
                    'capacity' => 2,
                    'max_adults' => 2,
                    'max_children' => 0,
                    'base_price' => 1000000,
                    'size_sqm' => 28,
                    'bed_type' => 'Twin',
                    'quantity' => 20,
                ],
            ],
            'resort' => [
                [
                    'type' => 'Beachfront Villa',
                    'description' => 'Villa tepi pantai dengan akses langsung ke pantai pribadi dan kolam renang pribadi.',
                    'capacity' => 6,
                    'max_adults' => 4,
                    'max_children' => 2,
                    'base_price' => 4000000,
                    'size_sqm' => 150,
                    'bed_type' => 'King + Twin',
                    'quantity' => 3,
                ],
                [
                    'type' => 'Garden Suite',
                    'description' => 'Suite dengan taman pribadi dan pemandangan alam yang menakjubkan.',
                    'capacity' => 4,
                    'max_adults' => 3,
                    'max_children' => 2,
                    'base_price' => 2800000,
                    'size_sqm' => 70,
                    'bed_type' => 'King Size',
                    'quantity' => 8,
                ],
                [
                    'type' => 'Deluxe Room',
                    'description' => 'Kamar deluxe dengan balkon menghadap ke taman atau kolam renang.',
                    'capacity' => 3,
                    'max_adults' => 2,
                    'max_children' => 1,
                    'base_price' => 1800000,
                    'size_sqm' => 40,
                    'bed_type' => 'Queen Size',
                    'quantity' => 12,
                ],
            ],
            'budget' => [
                [
                    'type' => 'Standard Double',
                    'description' => 'Kamar standar dengan tempat tidur ganda dan fasilitas dasar.',
                    'capacity' => 2,
                    'max_adults' => 2,
                    'max_children' => 1,
                    'base_price' => 500000,
                    'size_sqm' => 20,
                    'bed_type' => 'Double',
                    'quantity' => 15,
                ],
                [
                    'type' => 'Standard Twin',
                    'description' => 'Kamar standar dengan dua tempat tidur single.',
                    'capacity' => 2,
                    'max_adults' => 2,
                    'max_children' => 0,
                    'base_price' => 450000,
                    'size_sqm' => 18,
                    'bed_type' => 'Twin',
                    'quantity' => 20,
                ],
                [
                    'type' => 'Single Room',
                    'description' => 'Kamar single yang nyaman untuk solo traveler.',
                    'capacity' => 1,
                    'max_adults' => 1,
                    'max_children' => 0,
                    'base_price' => 350000,
                    'size_sqm' => 15,
                    'bed_type' => 'Single',
                    'quantity' => 10,
                ],
            ],
            default => [
                [
                    'type' => 'Standard Room',
                    'description' => 'Kamar standar yang nyaman.',
                    'capacity' => 2,
                    'max_adults' => 2,
                    'max_children' => 1,
                    'base_price' => 800000,
                    'size_sqm' => 25,
                    'bed_type' => 'Double',
                    'quantity' => 10,
                ],
            ],
        };
    }
}
