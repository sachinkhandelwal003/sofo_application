<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use App\Helper\Helper;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::where('status', 1)->get()->map(function ($category) {
            return [
                'id'    => $category->id,
                'name'  => $category->name,
                'status'  => $category->status,
                'image' => Helper::showImage($category->image, true),
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $categories
        ], 200); 
    }
}
