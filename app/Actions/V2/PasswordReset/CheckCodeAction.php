<?php

namespace App\Actions\V2\PasswordReset;

use Illuminate\Http\Exceptions\HttpResponseException;

class CheckCodeAction 
{
    public function execute($passwordReset, $code)
    {   
        if ($passwordReset->code != $code) {

            $passwordReset->total_attempt = $passwordReset->total_attempt + 1;

            if ($passwordReset->total_attempt == 5) {
                $passwordReset->block_attempt = now()->addMinutes(5);
            }

            $passwordReset->save();

            throw new HttpResponseException(response()->json([
                'message' => 'Please enter a valid code.'
            ], 403));
        }
    }
}
