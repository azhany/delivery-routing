<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Provider extends Authenticatable
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

    public function referredBy()
    {
        return $this->belongsTo(\App\Models\Provider::class, 'referred_by');
    }

    public function phoneVerify()
    {
        return $this->morphOne(\App\Models\PhoneVerify::class, 'phoneverifyable');
    }

    public function passwordReset()
    {
        return $this->morphOne(\App\Models\PasswordReset::class, 'passwordresetable');
    }

    public function application()
    {
        return $this->hasMany(\App\Models\ProviderApplication::class, 'provider_id');
    }
}
