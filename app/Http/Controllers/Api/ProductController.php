<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @param Request $request
         * @return JsonResponse
         */
      

public function indexByCategory($categoryId = null, $location = null): JsonResponse
{
    $query = Store::with('category');
    
    if ($categoryId) {
        $category = Category::find($categoryId);
        
        if (!$category) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'Category not found',
                'data' => []
            ], 404);
        }
        
        $query->where('category_id', $categoryId);
    }
    
    if ($location) {
        $decodedLocation = urldecode($location);
        $query->where('location', 'like', '%' . $decodedLocation . '%');
    }
    
    $stores = $query->latest()->paginate();
    
    $transformedStores = $stores->map(function ($store) {
        return [
            'id' => $store->id,
            'title' => $store->title,
            'image_url' => $store->image ? $this->showImage($store->image) : null,
            'category' => $store->category->name ?? 'Uncategorized',
            'location' => $store->location ?? 'Not specified',
            'contact' => $store->contact ?? 'Not available',
            'email' => $store->email ?? 'Not provided',
            'about' => $store->about ?? 'No description',
            'rating' => $store->rating ?? 0,
            'created_at' => $store->created_at->format('Y-m-d H:i:s')
        ];
    });
    
    $message = 'All stores';
    if ($categoryId && $location) {
        $message = sprintf(
            "Stores in category '%s' near '%s'",
            $category->name,
            urldecode($location)
        );
    } elseif ($categoryId) {
        $message = "Stores in category: " . $category->name;
    }
    
    return response()->json([
        'status' => true,
        'message' => $message,
        'data' => $transformedStores,
        'meta' => [
            'current_page' => $stores->currentPage(),
            'last_page' => $stores->lastPage(),
            'per_page' => $stores->perPage(),
            'total' => $stores->total(),
        ]
    ], 201); 
}
        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return JsonResponse
         */
        public function store(Request $request): JsonResponse
{
    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'about' => 'nullable|string',
            'delivery' => 'nullable|boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'nullable|in:active,inactive,draft',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            $validated['image'] = $this->saveFile($request->file('image'), 'stores');
        }

        // Create store
        $store = Store::create($validated);

        return response()->json([
            'status' => true,
            'status_code' => 201,
            'message' => 'Store created successfully',
            'data' => [
                'id' => $store->id,
                'title' => $store->title,
                'image_url' => $store->image ? $this->showImage($store->image) : null,
                'category' => $store->category->name,
                'location' => $store->location,
                'contact' => $store->contact,
                'email' => $store->email,
                'website' => $store->website,
                'about' => $store->about,
                'delivery' => (bool)$store->delivery,
                'rating' => (float)$store->rating,
                'status' => $store->status,
                'created_at' => $store->created_at->format('Y-m-d H:i:s')
            ]
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => false,
            'status_code' => 422,
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'status_code' => 500,
            'message' => 'Failed to create store',
            'error' => $e->getMessage()
        ], 500);
    }
}

        /**
         * Display the specified resource.
         *
         * @param Store $product
         * @return JsonResponse
         */
        public function show(Product $product): JsonResponse
        {
            return response()->json([
                'data' => $this->transformProduct($product->load('category'))
            ]);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param Store $product
         * @return JsonResponse
         */
        public function update(Request $request, Store $product): JsonResponse
        {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'location' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:50',
                'email' => 'nullable|email|max:100',
                'website' => 'nullable|url|max:255',
                'about' => 'nullable|string',
                'delivery' => 'nullable|boolean',
                'rating' => 'nullable|numeric|min:0|max:5',
                'status' => 'nullable|in:active,inactive,draft',
                'category_id' => 'nullable|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle image upload if new image is provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }
                $validated['image'] = $this->saveFile($request->file('image'), 'products');
            }

            $product->update($validated);

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $this->transformProduct($product)
            ]);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param Product $product
         * @return JsonResponse
         */
        public function destroy(Store $product): JsonResponse
        {
            // Delete associated image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully'
            ]);
        }

        /**
         * Save uploaded file to storage
         *
         * @param UploadedFile|null $image
         * @param string $folder
         * @return string|null
         */
        public static function saveFile(UploadedFile|null $image, string $folder = 'admin'): ?string
        {
            if ($image) {
                $filename = time() . '_' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/'.$folder, $filename);
                return $folder.'/'.$filename;
            }
            return null;
        }

        /**
         * Transform product data including image URL
         *
         * @param Product $product
         * @return array
         */
        private function transformProduct(Product $product): array
        {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'location' => $product->location,
                'contact' => $product->contact,
                'email' => $product->email,
                'website' => $product->website,
                'about' => $product->about,
                'delivery' => $product->delivery,
                'rating' => $product->rating,
                'status' => $product->status,
                'image_url' => $this->showImage($product->image),
                'category_id' => $product->category_id,
                'category' => $product->category,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        }

        /**
         * Get full URL for the image
         *
         * @param string|null $filename
         * @param bool $showDefault
         * @return string|null
         */
        public static function showImage(?string $filename, bool $showDefault = false): ?string
        {
            if ($filename && Storage::disk('public')->exists($filename)) {
                return asset('storage/' . $filename);
            }

            return $showDefault ? asset('assets/img/img-not-found.png') : null;
        }
        public function storeDetails($id): JsonResponse
        {
            $store = Store::with('category')->find($id);

            if (!$store) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Store not found',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Store details fetched successfully',
                'data' => [
                    'id' => $store->id,
                    'title' => $store->title,
                    'image_url' => $this->showImage($store->image, true),
                    'category' => $store->category->name ?? 'Uncategorized',
                    'category_id' => $store->category_id,
                    'location' => $store->location,
                    'contact' => $store->contact,
                    'email' => $store->email,
                    'website' => $store->website,
                    'about' => $store->about,
                    'delivery' => (bool)$store->delivery,
                    'rating' => (float)$store->rating,
                    'status' => $store->status,
                    'created_at' => $store->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $store->updated_at->format('Y-m-d H:i:s')
                ]
            ], 201);

        }



    }