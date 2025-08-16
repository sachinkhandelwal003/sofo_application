<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Addtocart;
use App\Helper\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddToCartController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:storeiteams,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user_id = Auth::id();
        $product_id = $request->product_id;

        $cartItem = Addtocart::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = Addtocart::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Item added to cart successfully',
            'cart' => $cartItem
        ]);
    }

   public function index()
   {
        $user_id = Auth::id();
        $cartData = Addtocart::with('product')
            ->where('user_id', $user_id)
            ->get();
        $cartItems = $cartData->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'product_name' => $item->product->name ?? null,
                'product_price' => $item->product->price ?? null,
                'product_image' => Helper::showImage($item->product->image ?? null, true),
            ];
        })->toArray(); // 👈 Convert to plain array

        $shippingFee = $cartData->first()->shipping_fee ?? 0;

        return response()->json([
            'status' => true,
            'items' => $cartItems,
            'shipping_fee' => $shippingFee,
        ]);

    }

    public function updateProductQty(Request $request){
        $userId= Auth::id();

        $validator = Validator::make($request->all(), [
             'cart_id'   => 'required|integer',
            'product_id' => 'required|exists:storeiteams,id',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ],422); 
        } 
        
        $cartItem = Addtocart::where('user_id', $userId)->where('id',$request->cart_id)->where('product_id',$request->product_id)->first();
        if(!empty($cartItem)){

            $cartItem->product_id  = $request->product_id;
            $cartItem->quantity    = $request->quantity;
            $cartItem->save();
             
              $cartItem->load('product');
             return response()->json([
                'status' => true,
                 'message' => 'Qty updated successfully',
                 'data'  => $cartItem,
               
            ]);

        }else{
              return response()->json([
                'status' => false,
                'message' => 'Data not found',
               
            ],422); 
        }

    }

    public function destroy($id)
    {
        $user_id = Auth::id();
        $item = Addtocart::where('id', $id)->where('user_id', $user_id)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}
