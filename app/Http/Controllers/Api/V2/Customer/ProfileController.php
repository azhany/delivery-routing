<?php

namespace App\Http\Controllers\Api\V2\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request) 
    {
        return response()->json([
            'success' => true,
            'data' => [
                'first_name' => $request->user()->first_name,
                'email' => $request->user()->email,
                'phone_code' => $request->user()->phone_code,
                'phone_number' => $request->user()->phone_number,
            ]
        ], 200);
    }
}
