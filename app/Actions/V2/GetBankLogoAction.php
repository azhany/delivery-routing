<?php

namespace App\Actions\V2;

use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Bank;

class GetBankLogoAction
{
    public function execute($id)
    {   
        if ($bank = Bank::find($id)) {
            $path = storage_path('app/'. $bank->logo);

            if (file_exists($path)) {
                return $path;
            }
        }

        throw new HttpResponseException(response()->json([
            'message' => 'Image not found.',
        ], 404));
    }
}
