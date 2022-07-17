<?php

namespace App\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\UnverifyAccount;

class PhoneVerifyValidToken
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

        $token =  explode('.', $request->token);

        if (!isset($token[1])) {
            $this->responseException();
        }

        $unverifyAccount = UnverifyAccount::find($token[0]);

        if (!$unverifyAccount) {
            $this->responseException();
        }

        $generatedSignature = hash_hmac('sha256', $unverifyAccount->id, $unverifyAccount->email);

        if ($generatedSignature != $token[1]) {
            $this->responseException();
        }

        $request->merge(['unverifyAccount' => $unverifyAccount]);

        if (!$request->routeIs('provider.phone.notice') || !$request->routeIs('customer.phone.notice')) {
            $request->merge(['phoneVerify' => $unverifyAccount->phoneVerify]);
        }

        return $next($request);
    }

    public function responseException()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Invalid request.',
        ], 403));
    }
}
