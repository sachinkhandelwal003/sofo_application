<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class TwilioCallController extends Controller
{
    protected $twilio;
	public function __construct(TwilioService $twilio)
	{
		$this->twilio = $twilio;
	}
	public function call(Request $request)
	{
	
		$request->validate([
			'to' => 'required|string'
		]);
		
		try {
			$call = $this->twilio->makeCall($request->to);
			return response()->json(['success' => true, 'sid' => $call->sid]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
		}
	}

}
