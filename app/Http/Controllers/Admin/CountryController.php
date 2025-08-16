<?php

namespace App\Http\Controllers\Admin;


use App\Models\State;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\View\View;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $data = Country::select('id', 'name','phonecode');
            return Datatables::of($data)
               
                ->rawColumns([ ''])
                ->make(true);
        }
        return view('admin.countries.index');
    }

}
