<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    public function passwordresetable()
    {
        return $this->morphTo();
    }
}
