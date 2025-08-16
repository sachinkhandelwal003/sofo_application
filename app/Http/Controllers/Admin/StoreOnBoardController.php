<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use App\Models\Storeiteam;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class StoreOnBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Vendor::with('user')->select('id', 'shop_name', 'gst_no', 'pan_no', 'tanno', 'address', 'status', 'app_user_id', 'created_at');

            return Datatables::of($data)
                ->editColumn('created_at', function ($row) {
                    return $row['created_at']->format('d M, Y');
                })
                ->addColumn('app_user_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->editColumn('status', function ($row) {
                    return $row['status'] == 1 
                        ? '<small class="badge fw-semi-bold rounded-pill status badge-light-success">Active</small>' 
                        : '<small class="badge fw-semi-bold rounded-pill status badge-light-danger">Inactive</small>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex align-items-center">';

                    if (Helper::userCan(104, 'can_add')) {
                        $btn .= '<a href="'.route('admin.stores.items', $row['id']).'" class="btn btn-sm btn-primary me-2">View Items</a>';
                    }

                    $btn .= '<div class="dropdown">';
                    $btn .= '<button class="text-600 btn-reveal dropdown-toggle btn btn-link btn-sm" id="drop" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs--1"></span></button>';
                    $btn .= '<div class="dropdown-menu" aria-labelledby="drop">';
                    if (Helper::userCan(104, 'can_edit')) {
                        $btn .= '<a class="dropdown-item" href="' . route('admin.cms.edit', $row['id']) . '">Edit</a>';
                    }
                    $btn .= '</div></div></div>';

                    return Helper::userAllowed(104) ? $btn : '';
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('created_at', $order);
                })
                ->rawColumns(['action', 'app_user_name', 'status'])
                ->make(true);
        }
        return view('admin.stores.index');
    }

    public function viewItems(Request $request, $vendorId): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Storeiteam::where('store_id', $vendorId)
                ->select('id', 'name', 'image', 'price', 'about', 'brand', 'size', 'status', 'created_at');

            return Datatables::of($data)
                ->editColumn('created_at', function ($row) {
                    return $row['created_at']->format('d M, Y');
                })
                 ->editColumn('image', function ($row) {
                    $btn = '<div class="img-group"><img class="" src="' . asset('storage/' . $row['image']) . '" alt=""></div>';
                    return $btn;
                })
                ->editColumn('status', function ($row) {
                    return $row['status'] == 1
                        ? '<small class="badge fw-semi-bold rounded-pill status badge-light-success">Active</small>'
                        : '<small class="badge fw-semi-bold rounded-pill status badge-light-danger">Inactive</small>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">';
                    $btn .= '<button class="text-600 btn-reveal dropdown-toggle btn btn-link btn-sm" id="drop" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs--1"></span></button>';
                    $btn .= '<div class="dropdown-menu" aria-labelledby="drop">';
                    if (Helper::userCan(104, 'can_edit')) {
                        $btn .= '<a class="dropdown-item" href="#">Edit Item</a>';
                    }
                    $btn .= '</div></div>';

                    return Helper::userAllowed(104) ? $btn : '';
                })
                ->rawColumns(['action', 'image','status'])
                ->make(true);
        }

        $vendor = Vendor::findOrFail($vendorId);
        return view('admin.stores.viewiteam', compact('vendor'));
    }
   
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:10000'],
            'status' => ['required', 'integer'],
            'image' => ['image', 'mimes:jpg,png,jpeg', 'max:5048']
        ]);

        $data = [...$validated, 'image' => 'cms/image.png'];
        if ($request->file('image')) {
            $data['image'] = Helper::saveFile($request->file('image'), 'cms');
        }

        Cms::create($data);
        return to_route('admin.cms')->withSuccess('Cms Added Successfully..!!');
    }

    public function edit($id): View|RedirectResponse
    {
        $cms = Cms::find($id);
        if (!$cms) {
            return to_route('admin.cms')->withError('Cms Not Found..!!');
        }
        return view('admin.cms.edit', compact('cms'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $cms = Cms::find($id);
        if (!$cms) {
            return to_route('admin.cms')->withError('Cms Not Found..!!');
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:10000'],
            'status' => ['required', 'integer'],
            'image' => ['image', 'mimes:jpg,png,jpeg', 'max:5048']
        ]);

        if ($request->file('image')) {
            Helper::deleteFile($cms->image);
            $data['image'] = Helper::saveFile($request->file('image'), 'cms');
        }

        $cms->update($data);
        return to_route('admin.cms')->withSuccess('Cms Updated Successfully..!!');
    }
    
    public function delete(Request $request): JsonResponse
    {
        return Helper::deleteRecord(new Cms, $request->id);
    }
}
