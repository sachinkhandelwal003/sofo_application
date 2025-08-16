<?php

namespace App\Http\Controllers\Admin;

use App\Models\Storeiteam;
use App\Models\Store;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class StoreIteamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Storeiteam::select(
                'storeiteams.id',
                'storeiteams.name',
                'storeiteams.image',
                'storeiteams.price',
                'storeiteams.status',
                'storeiteams.about',
                'storeiteams.brand',
                'storeiteams.size',
                'storeiteams.created_at',
                'stores.title as store_title'
            )
            ->leftJoin('stores', 'storeiteams.store_id', '=', 'stores.id');
            return Datatables::of($data)
                ->editColumn('image', function ($row) {
                    $btn = '<div class="img-group"><img class="" src="' . asset('storage/' . $row['image']) . '" alt=""></div>';
                    return $btn;
                })
                ->editColumn('store_title', function ($row) {
                        return $row->store_title ?? 'N/A';
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
                        $btn .= '<a class="dropdown-item" href="' . route('admin.cms.edit', $row['id']) . '">Edit</a>';
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
                ->rawColumns(['action', 'image','store_title', 'status'])
                ->make(true);
        }
        return view('admin.storeiteams.index');
    }

    public function add(): View
    {
        $store = Store::where('status', 1)->get(); 
        return view('admin.storeiteams.add', compact('store'));
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_id' => ['required'],
            'name'     => ['required'],
            'price'    => ['required'],
            'about'    => ['required'],
            'brand'    => ['required'],
            'size'    => ['required'],
            'status'   => ['required'],
            'image'    => ['nullable']
        ]);

        $data = [...$validated, 'image' => ''];

        if ($request->file('image')) {
            $data['image'] = Helper::saveFile($request->file('image'), 'storeiteam');
        }

        Storeiteam::create($data);
        return to_route('admin.store-iteam')->withSuccess('Store Item Added Successfully..!!');
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
            'title'         => ['required', 'string', 'max:200'],
            'description'   => ['required', 'string', 'max:10000'],
            'status'        => ['required', 'integer'],
            'image'         => ['image', 'mimes:jpg,png,jpeg', 'max:5048']
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
