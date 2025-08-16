<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliverySlot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class DeliverySlotController extends Controller
{
    
    public function store(Request $request)
    {

        $userId= Auth::id();
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:regular,scheduled',
            'storeiteams_id' =>'required|integer',
            'payment_methods_id' =>'required|integer',
            'shipping_addresses_id'  =>'required|integer',
            'delivery_date' => 'required|date|after:yesterday',  // ✅ after yesterday = today or future
            'delivery_time' => 'required|date_format:H:i',
        ], [
            'delivery_date.after' => 'The delivery date must be today or a future date.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ]); 
        }    

        $slot = DeliverySlot::create([
            'user_id'            => $userId,
            'payment_methods_id' => $request->payment_methods_id,
            'shipping_addresses_id' => $request->shipping_addresses_id,
            'storeiteams_id'     => $request->storeiteams_id,
            'type'               => $request->type,
            'delivery_date'      => $request->delivery_date,
            'delivery_time'      => $request->delivery_time,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Delivery time set successfully',
            'data' => $slot
        ]);
    }

    public function view(){
       
        $userId= Auth::id();
        $slots =  DeliverySlot::with(['storeItem','paymentMethod','shippingAddress'])
                    ->where('user_id', $userId)
                   // ->where('storeiteams_id',$storeiteamsId)
                    ->whereNull('deleted_at')
                    ->get();
        if(count($slots)>0){

             return response()->json([
            'status' => true,
            'message' => 'Get delivery schedule data list',
            'data' => $slots
        ]);

        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data not found'
            ]); 
        }
    }
}


 