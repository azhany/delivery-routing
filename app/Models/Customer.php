<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory;

    public function accessToken()
    {
        return $this->hasOne(\App\Models\OauthAccessToken::class, 'user_id');
    }

    public function emailVerify()
    {
        return $this->morphOne(\App\Models\EmailVerify::class, 'emailverifyable');
    }

    public function phoneVerify()
    {
        return $this->morphOne(\App\Models\PhoneVerify::class, 'phoneverifyable');
    }

    public function passwordReset()
    {
        return $this->morphOne(\App\Models\PasswordReset::class, 'passwordresetable');
    }
}
