<?php

namespace App\Actions\V2\PasswordReset;

class UpdatePasswordResetAction 
{
    public function execute($passwordReset)
    {   
        $passwordReset->update([
            'code' => rand(1000, 9999),
            'request_duration' => now()->addMinutes(3),
            'total_attempt' => null,
            'block_attempt' => null,
        ]);

        return $passwordReset;
    }
}
