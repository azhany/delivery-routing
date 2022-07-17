<?php

namespace App\Actions\V2;

class UpdateEmailAction 
{
    public function execute($user, $email)
    {   
        $user->update([
            'email' => $email,
            'email_verified_at' => null,
        ]);
    }
}
