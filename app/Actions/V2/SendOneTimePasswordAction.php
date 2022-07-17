<?php

namespace App\Actions\V2;

use Twilio\Rest\Client;

class SendOneTimePasswordAction 
{
    public function execute($phoneNumber, $message)
    {
        $twilioConfig = config('twilio');

        $twilio = new Client($twilioConfig['account_sid'], $twilioConfig['auth_token']);
        
        $twilio->messages->create(
            $phoneNumber, 
            [
                'from' => $twilioConfig['sms_from'],
                'body' => $message
            ]
        );
    }
}