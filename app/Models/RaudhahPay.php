<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class RaudhahPay extends Model
{
    use HasFactory;

    public function topup()
    {
        return $this->morphOne(\App\Models\Topup::class, 'topupable');
    }
}
