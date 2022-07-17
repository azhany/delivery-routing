<?php

namespace App\Actions\V2;

class UpdatePhoneAction 
{
    public function execute($phoneVerify)
    {
        $phoneVerify->phoneverifyable->update([
            'phone_code' => $phoneVerify->phone_code,
            'phone_number' => $phoneVerify->phone_number,
        ]);

        $phoneVerify->delete();
    }
}