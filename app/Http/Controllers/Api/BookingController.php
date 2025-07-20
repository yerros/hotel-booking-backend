<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Hotel;
use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'string|in:pending,confirmed,cancelled,completed',
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

        $customer = $request->user();
        $query = $customer->bookings()->with(['hotel', 'room']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 10);
        $bookings = $query->paginate($perPage);

        // Transform data
        $bookings->getCollection()->transform(function ($booking) {
            return [
                'id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'hotel' => [
                    'id' => $booking->hotel->id,
                    'name' => $booking->hotel->name,
                    'city' => $booking->hotel->city,
                    'address' => $booking->hotel->address,
                    'rating' => (float) $booking->hotel->rating,
                ],
                'room' => [
                    'id' => $booking->room->id,
                    'type' => $booking->room->type,
                    'description' => $booking->room->description,
                ],
                'guest_name' => $booking->guest_name,
                'guest_email' => $booking->guest_email,
                'guest_phone' => $booking->guest_phone,
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'nights' => $booking->nights,
                'guests' => $booking->guests,
                'total_price' => (float) $booking->total_price,
                'status' => $booking->status,
                'payment_method' => $booking->payment_method,
                'payment_status' => $booking->payment_status,
                'special_requests' => $booking->special_requests,
                'created_at' => $booking->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'bookings' => $bookings->items(),
                'pagination' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1|max:10',
            'payment_method' => 'nullable|string|max:50',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();
        $hotel = Hotel::find($request->hotel_id);
        $room = Rooms::find($request->room_id);

        // Verify room belongs to hotel
        if ($room->hotel_id !== $hotel->id) {
            return response()->json([
                'success' => false,
                'message' => 'Room does not belong to the selected hotel'
            ], 400);
        }

        // Check room capacity
        if ($request->guests > $room->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Number of guests exceeds room capacity'
            ], 400);
        }

        // Check room availability (basic check - you might want to implement more sophisticated logic)
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        
        $conflictingBookings = Bookings::where('room_id', $room->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                      ->orWhere(function ($q) use ($checkIn, $checkOut) {
                          $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                      });
            })
            ->exists();

        if ($conflictingBookings) {
            return response()->json([
                'success' => false,
                'message' => 'Room is not available for the selected dates'
            ], 400);
        }

        // Calculate nights and total price
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $nights * $room->price_per_night;

        // Generate booking number
        $bookingNumber = 'BK' . date('Ymd') . strtoupper(Str::random(6));

        // Create booking
        $booking = Bookings::create([
            'booking_number' => $bookingNumber,
            'customer_id' => $customer->id,
            'hotel_id' => $hotel->id,
            'room_id' => $room->id,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,
            'check_in_date' => $checkIn->toDateString(),
            'check_out_date' => $checkOut->toDateString(),
            'nights' => $nights,
            'guests' => $request->guests,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        // Load relationships
        $booking->load(['hotel', 'room']);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => [
                'booking' => [
                    'id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'hotel' => [
                        'id' => $booking->hotel->id,
                        'name' => $booking->hotel->name,
                        'city' => $booking->hotel->city,
                        'address' => $booking->hotel->address,
                    ],
                    'room' => [
                        'id' => $booking->room->id,
                        'type' => $booking->room->type,
                        'description' => $booking->room->description,
                    ],
                    'guest_name' => $booking->guest_name,
                    'guest_email' => $booking->guest_email,
                    'guest_phone' => $booking->guest_phone,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'nights' => $booking->nights,
                    'guests' => $booking->guests,
                    'total_price' => (float) $booking->total_price,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'special_requests' => $booking->special_requests,
                    'created_at' => $booking->created_at,
                ]
            ]
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $customer = $request->user();
        $booking = $customer->bookings()->with(['hotel', 'room'])->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'booking' => [
                    'id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                    'hotel' => [
                        'id' => $booking->hotel->id,
                        'name' => $booking->hotel->name,
                        'city' => $booking->hotel->city,
                        'address' => $booking->hotel->address,
                        'rating' => (float) $booking->hotel->rating,
                    ],
                    'room' => [
                        'id' => $booking->room->id,
                        'type' => $booking->room->type,
                        'description' => $booking->room->description,
                        'capacity' => $booking->room->capacity,
                        'price_per_night' => (float) $booking->room->price_per_night,
                    ],
                    'guest_name' => $booking->guest_name,
                    'guest_email' => $booking->guest_email,
                    'guest_phone' => $booking->guest_phone,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'nights' => $booking->nights,
                    'guests' => $booking->guests,
                    'total_price' => (float) $booking->total_price,
                    'status' => $booking->status,
                    'payment_method' => $booking->payment_method,
                    'payment_status' => $booking->payment_status,
                    'special_requests' => $booking->special_requests,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                ]
            ]
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $customer = $request->user();
        $booking = $customer->bookings()->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Booking is already cancelled'
            ], 400);
        }

        if ($booking->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel completed booking'
            ], 400);
        }

        // Check if cancellation is allowed (e.g., not within 24 hours of check-in)
        $checkInDate = Carbon::parse($booking->check_in_date);
        $now = Carbon::now();
        
        if ($now->diffInHours($checkInDate) < 24) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel booking within 24 hours of check-in'
            ], 400);
        }

        $booking->update([
            'status' => 'cancelled',
            'payment_status' => 'refunded' // You might want to handle actual refund logic
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'data' => [
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
            ]
        ]);
    }

    public function history(Request $request)
    {
        $customer = $request->user();
        
        $bookings = $customer->bookings()
            ->with(['hotel', 'room'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform data (same as index method)
        $bookings->getCollection()->transform(function ($booking) {
            return [
                'id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'hotel' => [
                    'id' => $booking->hotel->id,
                    'name' => $booking->hotel->name,
                    'city' => $booking->hotel->city,
                ],
                'room' => [
                    'type' => $booking->room->type,
                ],
                'check_in_date' => $booking->check_in_date,
                'check_out_date' => $booking->check_out_date,
                'nights' => $booking->nights,
                'guests' => $booking->guests,
                'total_price' => (float) $booking->total_price,
                'status' => $booking->status,
                'created_at' => $booking->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'bookings' => $bookings->items(),
                'pagination' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ]
            ]
        ]);
    }
}