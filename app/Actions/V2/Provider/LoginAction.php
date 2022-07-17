<?php

namespace App\Actions\V2\Provider;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use App\Models\Provider;

class LoginAction 
{
    public function execute($data)
    {   
        if ($user = Provider::where('phone_code', '+60')->where('phone_number', $data['phone_number'])->first()) {
            if (Hash::check($data['password'], $user->password)) {
                return $user;
            }
        }   

        throw new HttpResponseException(response()->json([
            'message' => 'Incorrect phone number or password.',
        ], 401));
    }
}
