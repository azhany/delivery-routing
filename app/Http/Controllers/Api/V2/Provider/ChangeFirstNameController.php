<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\UpdateFirstNameRequest;

class ChangeFirstNameController extends Controller
{
    public function __invoke(UpdateFirstNameRequest $request) 
    {
        $request->user()->update(['first_name' => $request->first_name]);

        return response()->json([
            'message' => 'Successfully updated.',
        ], 200);
    }
}
