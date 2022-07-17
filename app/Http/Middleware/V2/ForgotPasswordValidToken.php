<?php

namespace App\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\PasswordReset;

class ForgotPasswordValidToken
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

        $passwordReset = PasswordReset::find($token[0]);

        if (!$passwordReset) {
            $this->responseException();
        }

        $generatedSignature = hash_hmac('sha256', $passwordReset->id, $passwordReset->phone_number);

        if ($generatedSignature != $token[1]) {
            $this->responseException();
        }

        $request->merge(['passwordReset' => $passwordReset]);

        return $next($request);
    }

    public function responseException()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Invalid request.',
        ], 403));
    }
}