<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class UnverifyAccount extends Model
{
    use HasFactory;

    public function phoneVerify()
    {
        return $this->morphOne(\App\Models\PhoneVerify::class, 'phoneverifyable');
    }
}
