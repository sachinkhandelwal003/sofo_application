<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Vendor::select('id', 'shop_name', 'gst_no', 'pan_no', 'tanno','address','shop_image','categories','other_categories','status','created_at');
            return Datatables::of($data)
                ->editColumn('shop_image', function ($row) {
                    $btn = '<div class="img-group"><img class="" src="' . asset('storage/' . $row['shop_image']) . '" alt=""></div>';
                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    return $row['created_at']->format('d M, Y');
                })
    ->editColumn('status', function ($row) {
    switch ($row->status) {
        case 1:
            $label = 'Disapprove';
            $badgeClass = 'badge-light-danger';
            break;
        case 2:
            $label = 'Approve';
            $badgeClass = 'badge-light-success';
            break;
        default:
            $label = 'Default';
            $badgeClass = 'badge-light-secondary';
            break;
    }

    return '<span class="badge fw-semi-bold rounded-pill ' . $badgeClass . ' change-status" data-id="' . $row->id . '" style="cursor:pointer;">' . $label . '</span>';
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
                ->rawColumns(['action', 'shop_image', 'status'])
                ->make(true);
        }
        return view('admin.vendor.index');
    }
public function changeStatus(Request $request)
{
    $request->validate([
        'id' => 'required|exists:vendors,id',
        'status' => 'required|in:0,1',
    ]);

    $vendor = Vendor::findOrFail($request->id);
    $vendor->status = $request->status;
    $vendor->save();

    // If approved, update AppUser.become_vendor to 2
    if ($request->status == 1) {
        $vendor->user?->update(['become_vendor' => 2]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Vendor status updated successfully.'
    ]);
}


}
