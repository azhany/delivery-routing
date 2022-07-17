<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __invoke(UpdatePasswordRequest $request) 
    {
        $request->user()->update(['password' => Hash::make($request->new_password)]);

        return response()->json([
            'message' => 'Successfully updated.',
        ], 200);
    }
}
