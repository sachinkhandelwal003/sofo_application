<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAddressController extends Controller
{
    // 📥 Add address
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:Home,Office',
            'full_name' => 'required',
            'phone' => 'required|numeric',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);
        }

        $address = UserAddress::create([
            'user_id' => $userId,
            'type' => $request->type,
            'full_name'=>$request->full_name,
            'house_no'=>$request->house_no,
            'road_name'=>$request->road_name,
            'phone' => $request->phone,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Address added successfully',
            'data' => $address
        ]);
    }

    // 📋 List user addresses
    public function index()
    {
        $userId = Auth::id();
        $addresses = UserAddress::where('user_id', $userId)->whereNull('deleted_at')->orderBy('id','desc')->get();

        if ($addresses->count() > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Address list fetched successfully',
                'data' => $addresses
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No address found'
            ],422);
        }
    }

    // 📝 Update address
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'type' => 'required|in:Home,Office',
            'full_name' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);
        }

        $userId = Auth::id();
        $address = UserAddress::where('id', $request->id)->where('user_id', $userId)->first();

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Address not found'
            ],422);
        }
        $address->update([
            'type' => $request->type,
            'full_name'=>$request->full_name,
            'house_no'=>$request->house_no,
            'road_name'=>$request->road_name,
            'phone' => $request->phone,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully',
            'data' => $address
        ]);
    }

    // ❌ Delete address (soft delete)
    public function destroy(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
           
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422);
        }
        $userId = Auth::id();
        $address = UserAddress::where('id', $request->id)->where('user_id', $userId)->first();

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Address not found'
            ],422);
        }

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully'
        ]);
    }
}
