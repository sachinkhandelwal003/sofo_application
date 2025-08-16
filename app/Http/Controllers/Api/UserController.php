<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   public function __construct()
    {
       $this->middleware(["auth:api"]);
   }
    public function getUserDetails()
    {
        $user = Auth::user();
        $user['image'] = Helper::showImage($user->image);
        return response()->json([
            'status' => true,
            'user'   => $user,
        ]);
    }

    /**
     * Update user profile.
     */
   

    /**
     * Logout user by revoking token.
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
