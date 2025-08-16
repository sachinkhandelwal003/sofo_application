<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use App\Helper\Helper;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Vendor::with('user')->where('status', 1);

        if ($request->has('categories')) {
            $categoryIds = explode(',', $request->query('categories'));

            $query->where(function ($q) use ($categoryIds) {
                foreach ($categoryIds as $id) {
                    $q->orWhereJsonContains('categories', (string) trim($id));
                }
            });
        }

        $stores = $query->get();

        $stores->transform(function ($store) {
            return [
                'id' => $store->id,
                'shop_name' => $store->shop_name,
                'gst_no' => $store->gst_no,
                'shop_time' => $store->shop_time,
                'rating' => $store->rating,
                'pan_no' => $store->pan_no,
                'tanno' => $store->tanno,
                'address' => $store->address,
                'user_name' => $store->user?->name,
                'categories' => $store->category_names,
                'other_categories' => $store->other_categories,
                'email' => $store->email,
                'contact' => $store->contact,
                'website' => $store->website,
                'about' => $store->about,
                'delivery' => $store->delivery,
                'shop_image' => Helper::showImage($store->shop_image, true),
            ];
        });


        return response()->json([
            'success' => true,
            'data' => $stores
        ]);
    }

    public function show(Request $request, $id): JsonResponse
    {

        $store = Vendor::with('user')
            ->where('status', 1)
            ->find($id);
        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found or inactive'
            ], 404);
        }

        $responseData = [
            'id' => $store->id,
            'shop_name' => $store->shop_name,
            'gst_no' => $store->gst_no,
            'shop_time' => $store->shop_time,
            'rating' => $store->rating,
            'pan_no' => $store->pan_no,
            'tanno' => $store->tanno,
            'address' => $store->address,
            'user_name' => $store->user?->name,
            'categories' => $store->category_names,
            'other_categories' => $store->other_categories,
            'email' => $store->email,
            'contact' => $store->contact,
            'website' => $store->website,
            'about' => $store->about,
            'delivery' => $store->delivery,
            'shop_image' => Helper::showImage($store->shop_image, true),
        ];

        return response()->json([
            'success' => true,
            'data' => $responseData
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'shop_name' => 'nullable|string|max:255',
            'gst_no' => 'nullable|string|max:50',
            'shop_time' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'pan_no' => 'nullable|string|max:50',
            'tanno' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'categories' => 'nullable|array',
            'other_categories' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact' => 'nullable|string|max:15',
            'website' => 'nullable|url|max:255',
            'about' => 'nullable|string',
            'delivery' => 'nullable|boolean',
            'shop_image' => 'nullable|image|max:2048',
        ]);

        $vendor = Vendor::where('status', 1)->find($id);

        if (!$vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Active store not found'
            ], 404);
        }

        $vendor->fill($validated);

        if ($request->hasFile('shop_image')) {
            $vendor->shop_image = Helper::uploadImage($request->file('shop_image'), 'shop_images');
        }

        if ($request->has('categories')) {
            $vendor->categories = array_map('strval', $request->categories);
        }

        $vendor->save();

        return response()->json([
            'success' => true,
            'message' => 'Store profile updated successfully',
            'data' => $vendor
        ]);
    }

    public function allStores(): JsonResponse
    {
        $stores = Vendor::with('user')
            ->where('status', 1)
            ->get();

        $stores->transform(function ($store) {
            return [
                'id' => $store->id,
                'shop_name' => $store->shop_name,
                'gst_no' => $store->gst_no,
                'shop_time' => $store->shop_time,
                'rating' => $store->rating,
                'pan_no' => $store->pan_no,
                'tanno' => $store->tanno,
                'address' => $store->address,
                'user_name' => $store->user?->name,
                'categories' => $store->category_names,
                'other_categories' => $store->other_categories,
                'email' => $store->email,
                'contact' => $store->contact,
                'website' => $store->website,
                'about' => $store->about,
                'delivery' => $store->delivery,
                'shop_image' => \App\Helper\Helper::showImage($store->shop_image, true),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stores
        ]);
    }

}
