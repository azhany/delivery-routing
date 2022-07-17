<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {   
        $request->emailVerify->emailverifyable->update([
            'email_verified_at' => now()
        ]);

        $request->emailVerify->delete();

        return response()->view('v2.email-verify-success');
    }
}
