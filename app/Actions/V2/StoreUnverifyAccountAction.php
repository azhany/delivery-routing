<?php

namespace App\Actions\V2;

use App\Models\UnverifyAccount;
use Illuminate\Support\Facades\Hash;

class StoreUnverifyAccountAction 
{
    public function execute($type, $data)
    {   
        return UnverifyAccount::create([
            'type' => $type,
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'referred_by' => $data['referred_by'] ?? null,
            'password' => Hash::make($data['password']),
        ]);
    }
}
