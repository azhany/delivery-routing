<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V2\ResetPasswordRequest;
use App\Actions\V2\PasswordReset\ResetPasswordAction;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {   
        (new ResetPasswordAction)->execute($request->passwordReset, $request->password);

        return response()->json([
            'message' => 'Successfully reset.',
        ], 200);
    }
}
