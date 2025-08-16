<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AddToCartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\ItamController;
use App\Http\Controllers\Api\StoreItemController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\DeliverySlotController;
use App\Http\Controllers\Api\UserAddressController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\TwilioCallController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => "Api Working Fine."
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    // Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    // Route::get('/user-profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
    // Route::post('/update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/checkout', [CheckoutController::class, 'save']);
        Route::get('/checkout-list', [CheckoutController::class, 'view']);
        Route::post('/checkout/shipping/edit', [CheckoutController::class, 'editShipping']);
        Route::post('/checkout/paymen/edit', [CheckoutController::class, 'editPayment']);
        Route::post('/checkout/shipping/delete', [CheckoutController::class, 'deleteShipping']);
        Route::post('/checkout/payment/delete', [CheckoutController::class, 'deletePayment']);

        Route::post('/add-delivery-slot', [DeliverySlotController::class, 'store']);
        Route::get('/get-delivery-slot', [DeliverySlotController::class, 'view']);

        

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/user-profile', [AuthController::class, 'profile']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);


        Route::get('/addresses-list', [UserAddressController::class, 'index']);
        Route::post('/add-addresses', [UserAddressController::class, 'store']);
        Route::post('update-addresses', [UserAddressController::class, 'update']);
        Route::post('delete-addresses', [UserAddressController::class, 'destroy']);
    });
});




Route::get('/store/{id}', [StoreController::class, 'show']);
Route::get('/stores/{categoryIds}', [StoreController::class, 'index']);
Route::put('/stores/{id}', [StoreController::class, 'update']);
Route::get('/all-stores', [StoreController::class, 'allStores']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/add-to-cart', [AddToCartController::class, 'store']);
    Route::get('/cart', [AddToCartController::class, 'index']);
    Route::post('/cart-productQty-update', [AddToCartController::class, 'updateProductQty']);


    
    Route::delete('/cart/{id}', [AddToCartController::class, 'destroy']);
});


Route::apiResource('categories', CategoryController::class);

Route::post('/vendors', [VendorController::class, 'store'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/user/delete', [AuthController::class, 'deleteAccount']);
});
Route::middleware('auth:sanctum')->post('/add-items', [ItamController::class, 'store']);
Route::put('/items/update/{id}', [ItamController::class, 'update']);
Route::get('/store/items/{app_user_id}', [ItamController::class, 'listByUser']);


Route::get('clear-all', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('storage:link');
    return '<h1>Clear All</h1>';
});

Route::any('{path}', function () {
    return response()->json([
        'status' => false,
        'message' => 'Api not found..!!'
    ], 404);
})->where('path', '.*');





