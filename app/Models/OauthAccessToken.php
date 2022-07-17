<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    use HasFactory;

    public function provider()
    {
        return $this->belongsTo(\App\Models\Provider::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }
}
