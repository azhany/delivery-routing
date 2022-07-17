<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V2\Provider\ForgotPasswordSendRequest;
use App\Http\Requests\V2\ForgotPasswordVerifyRequest;
use App\Actions\V2\PasswordReset\DeletePasswordResetIfExistAction;
use App\Actions\V2\PasswordReset\StorePasswordResetAction;
use App\Actions\V2\PasswordReset\UpdatePasswordResetAction;
use App\Actions\V2\PasswordReset\CheckCodeAction;
use App\Jobs\V2\ProcessPasswordResetCode;

class ForgotPasswordController extends Controller
{
    public function send(ForgotPasswordSendRequest $request)
    {
        (new DeletePasswordResetIfExistAction)->execute($request->provider->passwordReset);

        $passwordReset = (new StorePasswordResetAction)->execute($request->provider->passwordReset(), $request->all());

        ProcessPasswordResetCode::dispatch($passwordReset);

        return response()->json([
            'message' => 'Successfully send OTP.',
            'data' => [
                'token' => $passwordReset->id . '.' . hash_hmac('sha256', $passwordReset->id, $passwordReset->phone_number),
                'request_duration' => $passwordReset->request_duration
            ]
        ], 201);
    }

    public function resend(Request $request)
    {
        $passwordReset = (new UpdatePasswordResetAction)->execute($request->passwordReset);

        ProcessPasswordResetCode::dispatch($passwordReset);

        return response()->json([
            'message' => 'Successfully resend OTP.',
            'data' => [
                'request_duration' => $passwordReset->request_duration
            ]
        ], 200);
    }

    public function verify(ForgotPasswordVerifyRequest $request)
    {
        (new CheckCodeAction)->execute($request->passwordReset, $request->code);

        return response()->json([
            'message' => 'Successfully verified.',
            'data' => [
                'token' => $request->passwordReset->id . '.' . hash_hmac('sha256', $request->passwordReset->id, $request->passwordReset->code),
            ]
        ], 200);
    }
}
