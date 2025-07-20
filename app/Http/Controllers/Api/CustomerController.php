<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function profile(Request $request)
    {
        $customer = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => [
                    'id' => $customer->id,
                    'full_name' => $customer->full_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'profile_image_url' => $customer->profile_image_url,
                    'email_verified_at' => $customer->email_verified_at,
                    'created_at' => $customer->created_at,
                    'total_bookings' => $customer->bookings()->count(),
                ]
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $customer = $request->user();

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password if trying to change password
        if ($request->has('password')) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }
        }

        $updateData = $request->only(['full_name', 'email', 'phone']);
        
        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $customer->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'customer' => [
                    'id' => $customer->id,
                    'full_name' => $customer->full_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'profile_image_url' => $customer->profile_image_url,
                ]
            ]
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();

        // Delete old avatar if exists
        if ($customer->profile_image_url) {
            $oldPath = str_replace('/storage/', '', $customer->profile_image_url);
            Storage::disk('public')->delete($oldPath);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $url = '/storage/' . $path;

        $customer->update(['profile_image_url' => $url]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'profile_image_url' => $url
            ]
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();

        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect'
            ], 400);
        }

        // Delete avatar if exists
        if ($customer->profile_image_url) {
            $path = str_replace('/storage/', '', $customer->profile_image_url);
            Storage::disk('public')->delete($path);
        }

        // Revoke all tokens
        $customer->tokens()->delete();

        // Delete customer account
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    }

    public function favorites(Request $request)
    {
        $customer = $request->user();
        
        // Note: You'll need to create a favorites table and relationship
        // For now, returning empty array
        return response()->json([
            'success' => true,
            'data' => [
                'favorites' => []
            ]
        ]);
    }

    public function addFavorite(Request $request, $hotel_id)
    {
        $customer = $request->user();
        
        $hotel = Hotel::find($hotel_id);
        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Hotel not found'
            ], 404);
        }

        // Note: Implement favorites table and relationship
        return response()->json([
            'success' => true,
            'message' => 'Hotel added to favorites'
        ]);
    }

    public function removeFavorite(Request $request, $hotel_id)
    {
        $customer = $request->user();
        
        // Note: Implement favorites table and relationship
        return response()->json([
            'success' => true,
            'message' => 'Hotel removed from favorites'
        ]);
    }
}