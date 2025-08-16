<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Storeiteam;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Helper\Helper;

class ItamController extends Controller
{
    public function listByUser($app_user_id): JsonResponse
    {
        // Get the vendor using app_user_id and check if active
        $store = Vendor::where('app_user_id', $app_user_id)
            ->where('status', 1)
            ->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found or inactive',
            ], 404);
        }

        // Get items by app_user_id
        $items = Storeiteam::where('app_user_id', $app_user_id)->get();

        $items->transform(function ($item) {
            return [
                'id' => $item->id,
                'app_user_id' => $item->app_user_id,
                'name' => $item->name,
                'image' => \App\Helper\Helper::showImage($item->image, true),
                'price' => $item->price,
                'about' => $item->about,
                'brand' => $item->brand,
                'size' => $item->size,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'store' => $store->shop_name,
            'data' => $items,
        ]);
    }


    public function store(Request $request): JsonResponse
    {
        $vendor = Vendor::where('app_user_id', auth()->id())->where('status', 1)->first();

        if (!$vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found or inactive',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable',
            'price' => 'required|numeric|min:0',
            'about' => 'nullable|string',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $imagePath = Helper::saveFile($request->file('image'), 'store_items');

        $item = Storeiteam::create([
            'app_user_id' => auth()->id(), // ✅ Set logged-in user ID
            'name' => $request->name,
            'image' => $imagePath,
            'price' => $request->price,
            'about' => $request->about,
            'brand' => $request->brand,
            'size' => $request->size,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item created successfully',
            'data' => [
                'id' => $item->id,
                'store_id' => $item->store_id,
                'name' => $item->name,
                'image' => Helper::showImage($item->image, true),
                'price' => $item->price,
                'about' => $item->about,
                'brand' => $item->brand,
                'size' => $item->size,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ],
        ]);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price' => 'nullable|numeric|min:0',
            'about' => 'nullable|string',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $item = Storeiteam::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        if ($request->has('name'))
            $item->name = $request->name;
        if ($request->has('price'))
            $item->price = $request->price;
        if ($request->has('about'))
            $item->about = $request->about;
        if ($request->has('brand'))
            $item->brand = $request->brand;
        if ($request->has('size'))
            $item->size = $request->size;
        if ($request->has('status'))
            $item->status = $request->status;

        if ($request->hasFile('image')) {
            $item->image = Helper::saveFile($request->file('image'), 'store_items');
        }

        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully',
            'data' => [
                'id' => $item->id,
                'store_id' => $item->store_id,
                'name' => $item->name,
                'image' => Helper::showImage($item->image, true),
                'price' => $item->price,
                'about' => $item->about,
                'brand' => $item->brand,
                'size' => $item->size,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]
        ]);
    }

}
