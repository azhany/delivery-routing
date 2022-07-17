<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class EmailVerify extends Model
{
    use HasFactory;

    public function emailverifyable()
    {
        return $this->morphTo();
    }
}
