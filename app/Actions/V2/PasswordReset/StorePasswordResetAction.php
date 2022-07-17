<?php

namespace App\Actions\V2\PasswordReset;

class StorePasswordResetAction 
{
    public function execute($passwordReset, $data)
    {   
        return $passwordReset->create([
            'phone_code' => $data['phone_code'],
            'phone_number' => $data['phone_number'],
            'code' => rand(1000, 9999),
            'request_duration' => now()->addMinutes(3),
        ]);
    }
}
