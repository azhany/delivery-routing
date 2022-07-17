<?php

namespace App\Http\Controllers\Api\V2\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V2\Customer\Phone\SendRequest;
use App\Http\Requests\V2\Phone\VerifyRequest;
use App\Actions\V2\Phone\DeletePhoneVerifyIfExistAction;
use App\Actions\V2\Phone\StorePhoneVerifyAction;
use App\Actions\V2\Phone\UpdatePhoneVerifyAction;
use App\Actions\V2\Phone\CheckCodeAction;
use App\Actions\V2\UpdatePhoneAction;
use App\Jobs\V2\ProcessPhoneVerifyCode;

class ChangePhoneController extends Controller
{
    public function send(SendRequest $request)
    {
        (new DeletePhoneVerifyIfExistAction)->execute($request->user()->phoneVerify);

        $phoneVerify = (new StorePhoneVerifyAction)->execute($request->user()->phoneVerify(), $request->all());

        ProcessPhoneVerifyCode::dispatch($phoneVerify);

        return response()->json([
            'message' => 'Successfully send OTP.',
            'data' => [
                'request_duration' => $phoneVerify->request_duration
            ]
        ], 201);
    }

    public function resend(Request $request)
    {
        $phoneVerify = (new UpdatePhoneVerifyAction)->execute($request->phoneVerify);

        ProcessPhoneVerifyCode::dispatch($phoneVerify);

        return response()->json([
            'message' => 'Successfully resend OTP.',
            'data' => [
                'request_duration' => $phoneVerify->request_duration
            ]
        ], 200);
    }

    public function verify(VerifyRequest $request)
    {
        (new CheckCodeAction)->execute($request->phoneVerify, $request->code);

        (new UpdatePhoneAction)->execute($request->phoneVerify);

        return response()->json([
            'message' => 'Successfully verified.',
        ], 200);
    }
}
