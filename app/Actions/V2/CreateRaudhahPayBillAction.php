<?php

namespace App\Actions\V2;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;

class CreateRaudhahPayBillAction
{
    public function execute($collection, $payload)
    {
        $raudhahPayConfig = config('raudhahpay');

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$raudhahPayConfig['token']}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post("{$raudhahPayConfig['url']}/collections/{$collection}/bills?include=product-collections.product", $payload);

        if ($response->clientError() || $response->serverError()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Server error.',
            ], 500));
        }

        return json_decode($response, true);
    }
}