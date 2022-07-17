<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class PhoneVerify extends Model
{
    use HasFactory;

    public function phoneverifyable()
    {
        return $this->morphTo();
    }
}
