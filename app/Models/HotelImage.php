<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HotelImage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'hotel_id',
        'is_primary',
        'display_order',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hotel-images')
            ->useDisk('public') // sesuai kebutuhan
            ->singleFile(false); // bisa multi upload
    }
}
