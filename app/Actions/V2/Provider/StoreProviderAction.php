<?php

namespace App\Actions\V2\Provider;

use App\Models\Provider;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class StoreProviderAction 
{
    public function execute($unverifyAccount, $phoneCode, $phoneNumber)
    {   
        return Provider::create([
            'first_name' => $unverifyAccount['first_name'],
            'email' => $unverifyAccount['email'],
            'phone_code' => $phoneCode,
            'phone_number' => $phoneNumber,
            'password' => $unverifyAccount['password'],
            'referral_code' => $this->generateReferralCode(),
            'referred_by' => $unverifyAccount['referred_by'],
            'file_secret_key' => $this->generateFileSecretKey(),
        ]);
    }

    protected function generateReferralCode()
    {
        return IdGenerator::generate([
            'table' => 'providers', 
            'field'=>'referral_code', 
            'length' => 14, 
            'prefix' => date('ym'),
            'reset_on_prefix_change' => true, 
        ]);
    }

    protected function generateFileSecretKey()
    {
        do {
            $fileSecretKey = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,5) . str_replace('.','',microtime(true)) . rand(0,999);
        } while(Provider::where('file_secret_key', $fileSecretKey)->exists());
    
        return $fileSecretKey;
    }
}
