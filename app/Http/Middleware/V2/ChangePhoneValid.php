<?php

namespace App\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePhoneValid
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
        if (!$phoneVerify = $request->user()->phoneVerify) {
            throw new HttpResponseException(response()->json([
                'message' => 'Invalid request.',
            ], 403));
        }

        $request->merge(['phoneVerify' => $phoneVerify]);

        return $next($request);
    }
}
