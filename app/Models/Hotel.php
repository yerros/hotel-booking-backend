<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Hotel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'rating',
        'category',
        'distance_from_center',
    ];

    // RELASI

    public function rooms()
    {
        return $this->hasMany(Rooms::class);
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(
            Amenities::class,
            'hotel_amenities',
            'hotel_id',      // foreign key on pivot table
            'amenity_id'     // related key on pivot table
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hotel-images')->useDisk('public');
    }
}
