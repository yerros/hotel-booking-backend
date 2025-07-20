<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:50',
            'category' => 'string|in:luxury,business,budget,resort',
            'city' => 'string|max:255',
            'sort_by' => 'string|in:price,rating,distance,name',
            'sort_order' => 'string|in:asc,desc',
            'min_price' => 'numeric|min:0',
            'max_price' => 'numeric|min:0',
            'min_rating' => 'numeric|min:0|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Hotel::with(['images', 'amenities']);

        // Apply filters
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'rating');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $hotels = $query->paginate($perPage);

        // Transform data
        $hotels->getCollection()->transform(function ($hotel) {
            return [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'description' => $hotel->description,
                'address' => $hotel->address,
                'city' => $hotel->city,
                'country' => $hotel->country,
                'latitude' => $hotel->latitude,
                'longitude' => $hotel->longitude,
                'rating' => (float) $hotel->rating,
                'category' => $hotel->category,
                'distance_from_center' => (float) $hotel->distance_from_center,
                'images' => $hotel->getMedia('hotel_images')->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'url' => $media->getUrl(),
                        'alt' => $media->getCustomProperty('alt_text'), // opsional
                    ];
                }),
                'amenities' => $hotel->amenities->map(function ($amenity) {
                    return [
                        'id' => $amenity->id,
                        'name' => $amenity->name,
                        'icon' => $amenity->icon,
                    ];
                }),
                'min_price' => $hotel->rooms()->min('base_price'),
                'created_at' => $hotel->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'hotels' => $hotels->items(),
                'pagination' => [
                    'current_page' => $hotels->currentPage(),
                    'last_page' => $hotels->lastPage(),
                    'per_page' => $hotels->perPage(),
                    'total' => $hotels->total(),
                    'from' => $hotels->firstItem(),
                    'to' => $hotels->lastItem(),
                ]
            ]
        ]);
    }

    public function show($id)
    {
        $hotel = Hotel::with(['images', 'amenities', 'rooms'])->find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'hotel' => [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'description' => $hotel->description,
                    'address' => $hotel->address,
                    'city' => $hotel->city,
                    'country' => $hotel->country,
                    'latitude' => $hotel->latitude,
                    'longitude' => $hotel->longitude,
                    'rating' => (float) $hotel->rating,
                    'category' => $hotel->category,
                    'distance_from_center' => (float) $hotel->distance_from_center,
                    'images' => $hotel->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'url' => $image->image_url,
                            'alt' => $image->alt_text,
                        ];
                    }),
                    'amenities' => $hotel->amenities->map(function ($amenity) {
                        return [
                            'id' => $amenity->id,
                            'name' => $amenity->name,
                            'icon' => $amenity->icon,
                        ];
                    }),
                    'rooms' => $hotel->rooms->map(function ($room) {
                        return [
                            'id' => $room->id,
                            'type' => $room->type,
                            'description' => $room->description,
                            'capacity' => $room->capacity,
                            'price_per_night' => (float) $room->price_per_night,
                            'amenities' => $room->amenities ?? [],
                            'images' => $room->images ?? [],
                        ];
                    }),
                    'created_at' => $hotel->created_at,
                ]
            ]
        ]);
    }

    public function rooms($id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found'
            ], 404);
        }

        $rooms = $hotel->rooms;

        return response()->json([
            'success' => true,
            'data' => [
                'hotel_id' => $hotel->id,
                'hotel_name' => $hotel->name,
                'rooms' => $rooms->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'type' => $room->type,
                        'description' => $room->description,
                        'capacity' => $room->capacity,
                        'price_per_night' => (float) $room->price_per_night,
                        'amenities' => $room->amenities ?? [],
                        'images' => $room->images ?? [],
                    ];
                })
            ]
        ]);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
            'check_in' => 'date|after_or_equal:today',
            'check_out' => 'date|after:check_in',
            'guests' => 'integer|min:1|max:10',
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Hotel::with(['images', 'amenities'])
            ->where(function ($q) use ($request) {
                $searchTerm = $request->query;
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('city', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });

        // If guests specified, filter by room capacity
        if ($request->has('guests')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('capacity', '>=', $request->guests);
            });
        }

        $perPage = $request->get('per_page', 10);
        $hotels = $query->paginate($perPage);

        // Transform data (same as index method)
        $hotels->getCollection()->transform(function ($hotel) {
            return [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'description' => $hotel->description,
                'city' => $hotel->city,
                'rating' => (float) $hotel->rating,
                'category' => $hotel->category,
                'distance_from_center' => (float) $hotel->distance_from_center,
                'images' => $hotel->images->take(1)->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->image_url,
                        'alt' => $image->alt_text,
                    ];
                }),
                'amenities' => $hotel->amenities->take(3)->map(function ($amenity) {
                    return [
                        'id' => $amenity->id,
                        'name' => $amenity->name,
                        'icon' => $amenity->icon,
                    ];
                }),
                'min_price' => $hotel->rooms()->min('price_per_night'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'query' => $request->query,
                'hotels' => $hotels->items(),
                'pagination' => [
                    'current_page' => $hotels->currentPage(),
                    'last_page' => $hotels->lastPage(),
                    'per_page' => $hotels->perPage(),
                    'total' => $hotels->total(),
                ]
            ]
        ]);
    }

    public function categories()
    {
        $categories = [
            ['id' => 'all', 'name' => 'All Hotels', 'icon' => 'apps-outline'],
            ['id' => 'luxury', 'name' => 'Luxury', 'icon' => 'diamond-outline'],
            ['id' => 'business', 'name' => 'Business', 'icon' => 'briefcase-outline'],
            ['id' => 'budget', 'name' => 'Budget', 'icon' => 'wallet-outline'],
            ['id' => 'resort', 'name' => 'Resort', 'icon' => 'sunny-outline'],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories
            ]
        ]);
    }
}
