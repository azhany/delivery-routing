<?php

namespace App\Actions\V2\Customer;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class LoginAction 
{
    public function execute($data)
    {   
        if ($user = Customer::where('phone_code', $data['phone_code'])->where('phone_number', $data['phone_number'])->first()) {
            if (Hash::check($data['password'], $user->password)) {
                return $user;
            }
        }   

        throw new HttpResponseException(response()->json([
            'message' => 'Incorrect phone number or password.',
        ], 401));
    }
}
