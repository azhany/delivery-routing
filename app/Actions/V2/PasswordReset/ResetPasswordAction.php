<?php

namespace App\Actions\V2\PasswordReset;

use Illuminate\Support\Facades\Hash;

class ResetPasswordAction 
{
    public function execute($passwordReset, $password)
    {   
        $passwordReset->passwordresetable->update([
            'password' => Hash::make($password)
        ]);

        $passwordReset->delete();
    }
}
