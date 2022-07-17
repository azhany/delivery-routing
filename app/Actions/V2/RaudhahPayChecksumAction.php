<?php

namespace App\Actions\V2;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class RaudhahPayChecksumAction 
{
    public function execute($data)
    {
        $generatedSignature = $this->generateSignature($data);

        if ($generatedSignature != $data['signature']) {
            throw new HttpResponseException(response()->json([
                'message' => 'Checksum failed.',
            ], 400));
        }
    }

    protected function generateSignature($data)
    {
        $signatureKey = config('raudhahpay.signature_key');
        ksort($data);
        $data = Arr::except($data, ['signature']);
        $data = array_map(function ($key, $value) {
            return "$key:$value";
        }, array_keys($data), $data);

        return hash_hmac('sha256', implode('|', $data), $signatureKey);
    }
}