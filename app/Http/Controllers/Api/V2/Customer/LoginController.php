<?php

namespace App\Http\Controllers\Api\V2\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\Customer\LoginRequest;
use App\Actions\V2\Customer\LoginAction;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {   
        $user = (new LoginAction)->execute($request->all());

        if ($token = $user->accessToken()->whereJsonContains('scopes', 'customer')->first()) {
            $token->delete();
        } 
        
        return response()->json([
            'message' => 'Successfully login.',
            'data' => [
                'token' => $user->createToken('Snapx', ['customer'])->accessToken
            ]
        ], 200);
    }
}
