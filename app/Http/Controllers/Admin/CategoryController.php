<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Category::select('id', 'name','image', 'status', 'created_at');
            return Datatables::of($data)
                ->editColumn('image', function ($row) {
                    $btn = '<div class="img-group"><img class="" src="' . asset('storage/' . $row['image']) . '" alt=""></div>';
                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    return $row['created_at']->format('d M, Y');
                })
                ->editColumn('status', function ($row) {
                    return $row['status'] == 1 ? '<small class="badge fw-semi-bold rounded-pill status badge-light-success"> Active</small>' : '<small class="badge fw-semi-bold rounded-pill status badge-light-danger"> Inactive</small>';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<button class="text-600 btn-reveal dropdown-toggle btn btn-link btn-sm" id="drop" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs--1"></span></button><div class="dropdown-menu" aria-labelledby="drop">';
                    if (Helper::userCan(104, 'can_edit')) {
                        $btn .= '<a class="dropdown-item" href="' . route('admin.categories.edit', $row['id']) . '">Edit</a>';
                    }
                    if (Helper::userAllowed(104)) {
                        return $btn;
                    } else {
                        return '';
                    }
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('created_at', $order);
                })
                ->rawColumns(['action', 'image', 'status'])
                ->make(true);
        }
        return view('admin.category.index');
    }

    public function add(): View
    {
        return view('admin.category.add');
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:200'],
            'status' => ['required', 'integer'],
            'image'         => ['required']
        ]);
     $data = [...$validated, 'image' => 'category/image.png'];
        if ($request->file('image')) {
            $data['image'] = Helper::saveFile($request->file('image'), 'category');
        }
        Category::create($data);

        return to_route('admin.categories')->withSuccess('Category Added Successfully..!!');
    }
   public function edit($id): View|RedirectResponse
{
    $category = Category::find($id);
    
    if (!$category) {
        return to_route('admin.categories')->withError('Category Not Found..!!');
    }
    
    return view('admin.category.edit', compact('category'));
}

public function update(Request $request, $id): RedirectResponse
{
    $category = Category::find($id);

    if (!$category) {
        return to_route('admin.categories')->withError('Category Not Found..!!');
    }

    $data = $request->validate([
        'name'   => ['required', 'string', 'max:200'],
        'status' => ['required', 'integer', 'in:0,1'],
        'image'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
    ]);

    if ($request->hasFile('image')) {
        if ($category->image) {
            Helper::deleteFile($category->image);
        }
        $data['image'] = Helper::saveFile($request->file('image'), 'cms');
    }

    $category->update($data);

    return to_route('admin.categories')->withSuccess('Category Updated Successfully..!!');
}
    public function delete(Request $request): JsonResponse
    {
        return Helper::deleteRecord(new Cms, $request->id);
    }
}
