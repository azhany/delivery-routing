<?php

namespace App\Actions\V2\PasswordReset;

class DeletePasswordResetIfExistAction 
{
    public function execute($passwordReset)
    {   
        if ($passwordReset) {
            $passwordReset->delete();
        }
    }
}
