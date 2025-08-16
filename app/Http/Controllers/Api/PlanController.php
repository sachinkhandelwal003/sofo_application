<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;

class PlanController extends Controller
{
    public function __construct()
    {
        //    $this->middleware(["auth:api"]);
    }
    public function getPlans()
    {
        $plans = Plan::where('status', 1)
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($plan) {
                $plan->icon = Helper::showImage($plan->icon);

                // Count the number of country IDs
                $ids = array_filter(explode(',', $plan->countries));
                $plan->country_count = count($ids);

                // Retrieve country names
                $countries = Country::whereIn('id', $ids)->pluck('name')->toArray();

                // Convert array of names into a comma-separated string
                $plan->countries_names = !empty($countries) ? implode(',', $countries) : '';

                return $plan;
            });

        return response()->json([
            'status' => true,
            'message' => 'Plans fetched successfully.',
            'data'   => $plans,
        ], 200);
    }

    /**
     * Update user profile.
     */


    /**
     * Logout user by revoking token.
     */
    public function planDetails(Request $request, $id)
    {
        $data = [];
        $plan = Plan::where('id', $id)->where('status', 1)->whereNull('deleted_at')->first();
        if (!$plan) {
            return response()->json([
                'status'    => false,
                'message'   => 'Plan not found.',
            ], 404);
        }
        if ($plan) {
            $plan->icon = Helper::showImage($plan->icon);
        }
        $ids = explode(',', $plan->countries) ?? [];

        $countries = Country::whereIn('id', $ids)->get()->pluck('name');

        $plan->countries = $countries;

        return response()->json([
            'status' => true,
            'message' => 'Plan fetched successfully.',
            'data'   => $plan,
        ], 200);
    }
}
