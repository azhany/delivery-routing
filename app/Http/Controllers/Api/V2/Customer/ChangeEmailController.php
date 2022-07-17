<?php

namespace App\Http\Controllers\Api\V2\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V2\Customer\EmailVerifySendRequest;
use App\Actions\V2\UpdateEmailAction;
use App\Jobs\V2\ProcessEmailVerify;

class ChangeEmailController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'data' => [
                'email' => $request->user()->email, 
                'is_verified' => ($request->user()->email_verified_at) ? true : false, 
            ]
        ], 200);
    }

    public function send(EmailVerifySendRequest $request)
    {
        (new UpdateEmailAction)->execute($request->user(), $request->email);

        ProcessEmailVerify::dispatch($request->user());

        return response()->json([
            'message' => 'Successfully send email.',
        ], 200);
    }

    public function resend(Request $request)
    {
        ProcessEmailVerify::dispatch($request->user());

        return response()->json([
            'message' => 'Successfully resend email.',
        ], 200);
    }
}