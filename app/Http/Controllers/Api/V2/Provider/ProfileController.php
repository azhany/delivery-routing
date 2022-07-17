<?php

namespace app\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request) 
    {
        return response()->json([
            'data' => [
                'profile_picture' => ($request->user()->profile_picture) ? urldecode(route('provider.file', ['path' => $request->user()->profile_picture])) : secure_asset('v2/no-profile-picture.svg'),
                'first_name' => $request->user()->first_name,
                'email' => $request->user()->email,
                'phone_number' => $request->user()->phone_number,
            ]
        ], 200);
    }
}
