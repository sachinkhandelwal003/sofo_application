<?php

namespace App\Http\Controllers\Api;

use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller; 
use App\Helper\Helper; 
use Illuminate\Support\Facades\DB;          // Added this
use Illuminate\Support\Facades\Storage;




class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'Full name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'mobile.required' => 'Mobile number is required',
            'mobile.unique' => 'This mobile number is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:app_users,email',
            'mobile' => 'required|string|max:20|unique:app_users,mobile',
            'password' => 'required|string|min:6',
        ], $messages);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = [$errorMessages[0]];
            }

            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        try {
            $user = AppUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'is_verified' => true,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'mobile' => $user->mobile
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Registration failed: '.$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Registration failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    if (!Auth::guard('app_user')->attempt([
        'email' => $request->email,
        'password' => $request->password
    ])) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials',
            'errors' => [
                'email' => ['The provided credentials are incorrect']
            ]
        ], 401);
    }
    $user = Auth::guard('app_user')->user();

    if (!$user->is_verified) {
        return response()->json([
            'status' => false,
            'message' => 'Account not verified',
        ], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'data' => [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]
    ], 201);
}





    /**
     * Logout user (Revoke the token)
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Logout failed: '.$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Logout failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $request->user()
            ], 200);

        } catch (\Exception $e) {
            \Log::error('User fetch failed: '.$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch user details',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
 * Get user profile
 */
public function profile(Request $request)
{
    try {
        $user = $request->user();

        // Optional: You can map the become_vendor value to a readable status if needed
        $vendorStatusText = match ((int) $user->become_vendor) {
            1 => 'Registered',
            2 => 'Approved',
            default => 'Default',
        };

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'is_verified' => $user->is_verified,
                  'profile_image_url' => $user->profile_image 
                    ? asset('storage/'.$user->profile_image)
                    : null,
                'become_vendor' => $user->become_vendor,
                'vendor_status' => $vendorStatusText, // optional for clarity
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]
        ], 200); // 200 is more appropriate than 201 for data retrieval

    } catch (\Exception $e) {
        \Log::error('Profile fetch failed: '.$e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Failed to fetch user profile',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

/**
 * Update user profile
 */
public function updateProfile(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|max:255|unique:app_users,email,'.$user->id,
        'mobile' => 'sometimes|required|string|max:20|unique:app_users,mobile,'.$user->id,
        'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'email.unique' => 'This email is already registered',
        'mobile.unique' => 'This mobile number is already registered',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        $user->fill($request->only(['name', 'email', 'mobile']));

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $oldImagePath = 'public/' . $user->profile_image;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }
            
            // Save new image using your helper function
            $imagePath = Helper::saveFile($request->file('profile_image'), 'profile_images');
            $user->profile_image = $imagePath;
        }

        $user->save();
        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'profile_image_url' => $user->profile_image 
                    ? asset('storage/'.$user->profile_image)
                    : null,
            ]
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Profile update failed: '.$e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Failed to update profile',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

public function deleteAccount(Request $request)
{
    try {
        $user = $request->user();

        // Optionally delete related data here if needed (e.g., posts, comments, etc.)

        // Delete profile image if exists
        if ($user->profile_image) {
            $imagePath = 'public/' . $user->profile_image;
            if (\Storage::exists($imagePath)) {
                \Storage::delete($imagePath);
            }
        }

        // Revoke all tokens
        $user->tokens()->delete();

        // Delete the user
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully'
        ], 201);

    } catch (\Exception $e) {
        \Log::error('Account deletion failed: '.$e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'Failed to delete account',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
}