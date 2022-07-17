<?php

namespace App\Actions\V2;

use App\Models\UnverifyAccount;

class DeleteUnverifyAccountIfExistAction 
{
    public function execute($type, $email)
    {   
        $unverifyAccount = UnverifyAccount::where([
            ['type', '=', $type],
            ['email', '=', $email],
        ])->first();

        if ($unverifyAccount) {
            $unverifyAccount->delete();
        }
    }
}
