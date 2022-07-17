<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\Provider\LoginRequest;
use App\Actions\V2\Provider\LoginAction;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {   
        $user = (new LoginAction)->execute($request->all());

        if ($token = $user->accessToken()->whereJsonContains('scopes', 'provider')->first()) {
            $token->delete();
        } 
        
        return response()->json([
            'message' => 'Successfully login.',
            'data' => [
                'token' => $user->createToken('Snapx', ['provider'])->accessToken
            ]
        ], 200);
    }
}
