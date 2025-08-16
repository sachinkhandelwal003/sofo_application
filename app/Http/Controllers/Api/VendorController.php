<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Helper\Helper; // adjust the namespace if needed


class VendorController extends Controller
{
    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shop_name' => 'required',
            'gst_no' => 'nullable',
            'pan_no' => 'nullable',
            'tanno' => 'nullable',
            'address' => 'nullable',
            'categories' => 'nullable',
            'shop_time' => 'nullable',
            'rating' => 'nullable',
            'other_categories' => 'nullable|string',
            'status' => 'nullable|in:0,1',
            'shop_image' => 'nullable|image|max:2048',
        ]);

        $validated['status'] = $validated['status'] ?? 0;

        $validated['app_user_id'] = auth('sanctum')->id();

        if ($request->hasFile('shop_image')) {
            $validated['shop_image'] = \App\Helper\Helper::saveFile($request->file('shop_image'), 'vendors');
        }

        // Save vendor
        $vendor = Vendor::create($validated);

        // Return response
        return response()->json([
            'message' => 'Vendor created successfully.',
            'data' => [
                'id' => $vendor->id,
                'shop_name' => $vendor->shop_name,
                'time' => $vendor->time,
                'rating' => $vendor->rating,
                'gst_no' => $vendor->gst_no,
                'pan_no' => $vendor->pan_no,
                'tanno' => $vendor->tanno,
                'shop_time' => $vendor->shop_time,
                'address' => $vendor->address,
                'categories' => $vendor->categories,
                'other_categories' => $vendor->other_categories,
                'status' => $vendor->status,
                'shop_image_url' => \App\Helper\Helper::showImage($vendor->shop_image, true),
                'created_at' => $vendor->created_at,
            ]
        ], 201);
    }





}
