<?php

namespace App\Actions\V2;

use Illuminate\Http\Exceptions\HttpResponseException;

class ThrowErrorIfFareNotExistAction 
{
    public function execute($fare)
    {
        if ($fare != null) {
            throw new HttpResponseException(response()->json([
                'message' => 'There is no fare set in database.',
            ], 404));
        }
    }
}