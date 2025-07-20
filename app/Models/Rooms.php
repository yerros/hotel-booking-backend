<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_type',
        'description',
        'capacity',
        'max_adults',
        'max_children',
        'base_price',
        'size_sqm',
        'bed_type',
        'quantity_available',
    ];

    // Relasi ke hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    // Relasi ke bookings
    public function bookings()
    {
        return $this->hasMany(Bookings::class);
    }
}
