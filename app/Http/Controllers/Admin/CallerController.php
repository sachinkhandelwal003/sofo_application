<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cms;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Caller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class CallerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Caller::whereNull('deleted_at');
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
                        $btn .= '<a class="dropdown-item" href="' . route('admin.callers.edit', $row['id']) . '">Edit</a>';
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
        return view('admin.callers.index');
    }

    public function add(): View
    {
        return view('admin.callers.add');
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'userId'          => ['required', 'string', 'max:20'],
            'name'            => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'unique:callers,email'],
            'mobile'          => ['required', 'digits:10', 'unique:callers,mobile', 'regex:' . config('constant.phoneRegExp')],
            'password'        => ['required', 'confirmed', 'min:8'],
            'status'          => ['required', 'integer'],
            'image'           => ['image', 'mimes:jpg,png,jpeg', 'max:5048']
        ]);

        $data = [...$validated, 'image' => 'callers/image.png'];
        if ($request->file('image')) {
            $data['image'] = Helper::saveFile($request->file('image'), 'callers');
        }

        $data['password'] = Hash::make($data['password']);

        Caller::create($data);
        return to_route('admin.callers')->withSuccess('Caller Added Successfully..!!');
    }

    public function edit($id): View|RedirectResponse
    {
        $caller = Caller::find($id);
        if (!$caller) {
            return to_route('admin.callers')->withError('Caller Not Found..!!');
        }
        return view('admin.callers.edit', compact('caller'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $caller = Caller::find($id);
        if (!$caller) {
            return to_route('admin.callers')->withError('Caller Not Found..!!');
        }

        $data = $request->validate([
            'userId'          => ['required', 'string', 'max:20'],
            'name'            => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'unique:callers,email,' . $caller->id],
            'mobile'          => ['required', 'digits:10', 'unique:callers,mobile,' . $caller->id, 'regex:' . config('constant.phoneRegExp')],
            'status'          => ['required', 'integer'],
            'image'           => ['image', 'mimes:jpg,png,jpeg', 'max:5048']
        ]);

        if ($request->file('image')) {
            Helper::deleteFile($caller->image);
            $data['image'] = Helper::saveFile($request->file('image'), 'callers');
        }

        if ($request->password) {
            $validated = $request->validate([
                'password'        => ['required', 'confirmed', 'min:8'],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $caller->update($data);
        return to_route('admin.callers')->withSuccess('Caller Updated Successfully..!!');
    }

    public function delete(Request $request): JsonResponse
    {
        return Helper::deleteRecord(new Caller, $request->id);
    }
}
