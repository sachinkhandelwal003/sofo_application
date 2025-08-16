<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // ✅ Save shipping + payment info
    public function save(Request $request)
    {
        
        $user_id = Auth::user()->id;
      
        $user = Validator::make($request->all(), [
            'shipping.storeiteams_id' => 'required|integer',
            'shipping.type' => 'required|in:Home,Office',
            'shipping.phone' => 'required|string|min:10',
            'shipping.city' => 'required|string',
            'shipping.state' => 'required|string',
           
            'payment.storeiteams_id' => 'required|integer',
            'payment.method' => 'required|in:Credit Card,Paypal',
            'payment.card_holder_name' => 'required_if:payment.method,Credit Card',
            'payment.card_number' => 'required_if:payment.method,Credit Card|min:16|max:19',
            'payment.expiry_date' => 'required_if:payment.method,Credit Card',
            'payment.paypal_email' => 'required_if:payment.method,Paypal|email',
        ]);

        if ($user->fails()) {
            return response()->json([
                 'status' => false,
                'message' => 'Validation failed',
                'errors' => $user->errors(),
            ], 422);
        }


         $shipping                   = new ShippingAddress;
         $shipping->user_id          = $user_id;
         $shipping->storeiteams_id   = $request['shipping']['storeiteams_id'];
         $shipping->type             = $request['shipping']['type'];
         $shipping->phone            = $request['shipping']['phone'];
         $shipping->city             = $request['shipping']['city'];
         $shipping->pincode          = $request['shipping']['pincode'];
         $shipping->state            = $request['shipping']['state'];
         $shipping->save();

        // // Save Payment
         $payment = new PaymentMethod;
        if ($request['payment']['method'] === 'Credit Card') {
            $card = $request['payment'];
                $payment->method            = $request['payment']['method'];
                $payment->card_holder_name  = $request['payment']['card_holder_name'];
                $payment->card_number       =  Crypt::encryptString($card['card_number']);
                $payment->card_last_digits  = $request['payment']['card_last_digits'];
                $payment->expiry_date       = $request['payment']['expiry_date'];
            } else {
                $payment->method            = $request['payment']['method'];
                $payment->paypal_email      = $request['payment']['paypal_email'];
                
            }
          $payment->user_id          = $user_id;
          $payment->storeiteams_id   = $request['payment']['storeiteams_id'];

          $payment->save();
        return response()->json([
            'status' => true,
            'message' => 'Checkout saved successfully.',
            'shipping' => $shipping,
            'payment' => $payment
        ]);
    }

    // ✅ View all active shipping & payment
    public function view()
    {

        $user_id = Auth::id(); // Shortcut to get authenticated user ID

            $shippingAddresses = ShippingAddress::where('user_id', $user_id)->select('id','user_id','storeiteams_id','type','phone','city','pincode','state')
            //with('storeItem')
               // ->where('user_id', $user_id)
              //  ->where('storeiteams_id',$storeiteamsId)
                ->whereNull('deleted_at')
                ->get();

            // Get payment methods with related store items (if relationship exists)
            $paymentMethods = PaymentMethod::where('user_id', $user_id)->select('id','user_id','storeiteams_id','method','card_holder_name','card_number','card_last_digits','expiry_date','paypal_email')
            //::with('storeItem')
                ->where('user_id', $user_id)
                //->where('storeiteams_id',$storeiteamsId)
                ->whereNull('deleted_at')
                ->get();
        if ($shippingAddresses->isNotEmpty() || $paymentMethods->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'shipping_addresses' => $shippingAddresses,
                'payment_methods' => $paymentMethods,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Data not found!',
        ],422);
       
    }

    // ✅ Edit shipping address
    public function editShipping(Request $request)
    {
        $validated = $request->validate([
            'shipping.shipping_id' =>'required',
            'shipping.type' => 'required|in:Home,Office',
            'shipping.phone' => 'required|string|min:10',
            'shipping.city' => 'required|string',
            'shipping.state' => 'required|string',
        ]);
        $user_id = Auth::id();
        $shipping = ShippingAddress::where('id',$request['shipping']['shipping_id'])->where('user_id',$user_id)->first();
        if(!empty($shipping )){
            
            $shipping->user_id   = $user_id;
            $shipping->type      = $request['shipping']['type'];
            $shipping->phone     = $request['shipping']['phone'];
            $shipping->city      = $request['shipping']['city'];
            $shipping->pincode   = $request['shipping']['pincode'];
            $shipping->state     = $request['shipping']['state'];

             $shipping->save();
            return response()->json([
                'status' => true,
                'message' => "shipping updated",
                'shipping_addresses' => $shipping
               
            ]);
        }else{
           return response()->json([
            'status' => false,
            'message' => 'Data not found!',
           ],422); 
        }

    }

    // ✅ Edit payment method
    public function editPayment(Request $request)
    {
        $validated = $request->validate([
            'payment.payment_id' => 'required',
            'payment.method' => 'required|in:Credit Card,Paypal',
            'payment.card_holder_name' => 'required_if:payment.method,Credit Card',
            'payment.card_number' => 'required_if:payment.method,Credit Card|min:16|max:19',
            'payment.expiry_date' => 'required_if:payment.method,Credit Card',
            'payment.paypal_email' => 'required_if:payment.method,Paypal|email',
        ]);
        
        $user_id = Auth::id();
        $payment = PaymentMethod::where('id',$request['payment']['payment_id'])->where('user_id',$user_id)->first();

            if(!empty($payment)){
                if ($request['payment']['method'] === 'Credit Card') {
                    $card = $request['payment'];
                        $payment->method            = $request['payment']['method'];
                        $payment->card_holder_name  = $request['payment']['card_holder_name'];
                        $payment->card_number       =  Crypt::encryptString($card['card_number']);
                        $payment->card_last_digits  = $request['payment']['card_last_digits'];
                        $payment->expiry_date       = $request['payment']['expiry_date'];
                    } else {
                        $payment->method            = $request['payment']['method'];
                        $payment->paypal_email      = $request['payment']['paypal_email'];
                        
                    }
                $payment->user_id   = $user_id;
                $payment->save();
                return response()->json([
                    'status' => true,
                    'message' => 'payment updated successfully.',
                     'payment' => $payment
                ]);
            }else{
                 return response()->json([
                    'status' => false,
                    'message' => 'Data not found!',
                ],422); 
            }
                

    }

    // ✅ Soft delete shipping
    public function deleteShipping(Request $request, )
    {
         $validated = $request->validate([
            'shipping.shipping_id' =>'required',
          
        ]);
        $user_id = Auth::id();
        $shipping = ShippingAddress::where('id',$request['shipping']['shipping_id'])->where('user_id',$user_id)->first();
        if(!empty($shipping )){
            
        return response()->json([ 'status' => true,'message' => 'Shipping address deleted.']);
        }
        return response()->json([
                    'status' => false,
                    'message' => 'Data not found!',
        ],422); 
    }

    // ✅ Soft delete payment
    public function deletePayment(Request $request)
    {
       $validated = $request->validate([
            'payment.payment_id' => 'required',
          
        ]);
        
        $user_id = Auth::id();
        $payment = PaymentMethod::where('id',$request['payment']['payment_id'])->where('user_id',$user_id)->first();
        if(!empty($payment)){
            $payment->delete();
            return response()->json(['status' => true,'message' => 'Payment method deleted.']);
        }else{
          return response()->json([
            'status' => false,
            'message' => 'Data not found!',
           ],422); 
        }


        
    }
}
