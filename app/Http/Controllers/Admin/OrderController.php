<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cms;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Caller;
use App\Models\Country;
use App\Models\Plan;
use Illuminate\View\View;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Plan::whereNull('deleted_at');
            return Datatables::of($data)
                ->editColumn('icon', function ($row) {
                    $btn = '<div class="img-group"><img class="" src="' . asset('storage/' . $row['icon']) . '" alt=""></div>';
                    return $btn;
                })
          
                ->editColumn('status', function ($row) {
                    return $row['status'] == 1 ? '<small class="badge fw-semi-bold rounded-pill status badge-light-success"> Active</small>' : '<small class="badge fw-semi-bold rounded-pill status badge-light-danger"> Inactive</small>';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<button class="text-600 btn-reveal dropdown-toggle btn btn-link btn-sm" id="drop" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs--1"></span></button><div class="dropdown-menu" aria-labelledby="drop">';
                    if (Helper::userCan(104, 'can_edit')) {
                        $btn .= '<a class="dropdown-item" href="' . route('admin.orders.edit', $row['id']) . '">Edit</a>';
                    }
                    if (Helper::userCan(104, 'can_delete')) {
                        $btn .= '<button class="dropdown-item text-danger delete" data-id="' . $row['id'] . '">Delete</button>';
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
                ->rawColumns(['action', 'icon', 'status'])
                ->make(true);
        }
        return view('admin.orders.index');
    }

    public function add(): View
    {
        $countries = Country::get()->toArray();
        return view('admin.orders.add', compact('countries'));
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sub_name'          => ['required', 'string', 'max:20'],
            'name'            => ['required', 'string', 'max:100'],
            'price'        => ['required', 'numeric'],
            'countries'        => ['required', 'array'],
            'countries.*'        => ['required'],
            'status'          => ['required', 'integer'],
            'icon'           => ['required','image', 'mimes:jpg,png,jpeg', 'max:5048']
        ]);

        $data = [...$validated, 'icon' => 'orders/image.png'];
        if ($request->file('icon')) {
            $data['icon'] = Helper::saveFile($request->file('icon'), 'orders');
        }

        $data['countries'] = !empty($request->countries) ? implode(',', $request->countries) : '';

       

        Plan::create($data);
        return to_route('admin.orders')->withSuccess('Plan Added Successfully..!!');
    }

    public function edit($id): View|RedirectResponse
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return to_route('admin.orders')->withError('Plan Not Found..!!');
        }
        $countries = Country::get()->toArray();
        return view('admin.orders.edit', compact('plan', 'countries'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return to_route('admin.orders')->withError('Plan Not Found..!!');
        }

        $data = $request->validate([
            'sub_name'          => ['required', 'string', 'max:20'],
            'name'            => ['required', 'string', 'max:100'],
            'price'        => ['required', 'numeric'],
            'countries'        => ['required', 'array'],
            'countries.*'        => ['required'],
            'status'          => ['required', 'integer'],
            'icon'           => ['nullable','image', 'mimes:jpg,png,jpeg', 'max:5048']
        ]);

        if ($request->file('icon')) {
            Helper::deleteFile($plan->image);
            $data['icon'] = Helper::saveFile($request->file('icon'), 'plans');
        }

        $data['countries'] = !empty($request->countries) ? implode(',', $request->countries) : '';

        $plan->update($data);
        return to_route('admin.orders')->withSuccess('Plan Updated Successfully..!!');
    }

    public function delete(Request $request): JsonResponse
    {
        return Helper::deleteRecord(new Plan, $request->id);
    }
}
