<?php

namespace App\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\EmailVerify;

class EmailVerifyValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->token) {
            $this->responseException();
        }

        $token = explode('.', $request->token);

        if (!isset($token[1])) {
            $this->responseException();
        }

        $emailVerify = EmailVerify::find($token[0]);

        if (!$emailVerify) {
            $this->responseException();
        }

        if ($emailVerify->expired_at < now()) {
            $emailVerify->delete();
            $this->responseException();
        }

        $generatedSignature = hash_hmac('sha256', $emailVerify->id, $emailVerify->emailverifyable->email);

        if ($generatedSignature != $token[1]) {
            $this->responseException();
        }

        $request->merge(['emailVerify' => $emailVerify]);

        return $next($request);
    }

    public function responseException()
    {
        throw new HttpResponseException(response()->view('v2.email-verify-fail'));
    }
}
