<?php

namespace App\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgotPasswordRequestDuration
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
        if ($request->passwordReset->request_duration > now()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Please wait before request another OTP.',
            ], 429));
        }  

        return $next($request);
    }
}
