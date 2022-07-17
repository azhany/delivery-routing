<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Fare extends Model
{
    use HasFactory;

    public function vehicleable()
    {
        return $this->morphTo();
    }
}
