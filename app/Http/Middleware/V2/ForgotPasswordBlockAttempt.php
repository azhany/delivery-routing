<?php

namespace App\Http\Middleware\V2;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgotPasswordBlockAttempt
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
        if ($request->passwordReset->block_attempt > now()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Too many attempts.',
                'data' => [
                    'block_attempt' => $request->passwordReset->block_attempt
                ]
            ], 429));
        }

        if ($request->passwordReset->block_attempt && $request->passwordReset->block_attempt < now()) {
            $request->passwordReset->update([
                'total_attempt' => null, 
                'block_attempt' => null, 
            ]);
        }

        return $next($request);
    }
}