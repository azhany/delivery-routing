<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\Provider\CreateAccountRequest;
use App\Actions\V2\DeleteUnverifyAccountIfExistAction;
use App\Actions\V2\StoreUnverifyAccountAction;

class CreateAccountController extends Controller
{
    public function __invoke(CreateAccountRequest $request)
    {   
        (new DeleteUnverifyAccountIfExistAction)->execute('provider', $request->email);

        $unverifyAccount = (new StoreUnverifyAccountAction)->execute('provider', $request->all());

        return response()->json([
            'message' => 'Successfully created.',
            'data' => [
                'token' => $unverifyAccount->id . '.' . hash_hmac('sha256', $unverifyAccount->id, $unverifyAccount->email)
            ]
        ], 201);
    }
}
