<?php

namespace App\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangeEmailValid
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
        if ($request->user()->email_verified_at) {
            throw new HttpResponseException(response()->json([
                'message' => 'Invalid request.',
            ], 403));
        }

        return $next($request);
    }
}
