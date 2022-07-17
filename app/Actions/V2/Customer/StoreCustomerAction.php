<?php

namespace App\Actions\V2\Customer;

use App\Models\Customer;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class StoreCustomerAction 
{
    public function execute($unverifyAccount, $phoneCode, $phoneNumber)
    {   
        return Customer::create([
            'first_name' => $unverifyAccount['first_name'],
            'email' => $unverifyAccount['email'],
            'phone_code' => $phoneCode,
            'phone_number' => $phoneNumber,
            'password' => $unverifyAccount['password'],
            'referral_code' => $this->generateReferralCode(),
            'referred_by' => $unverifyAccount['referred_by'],
        ]);
    }

    public function generateReferralCode()
    {
        return IdGenerator::generate([
            'table' => 'customers', 
            'field'=>'referral_code', 
            'length' => 14, 
            'prefix' => date('ym'),
            'reset_on_prefix_change' => true, 
        ]);
    }
}
