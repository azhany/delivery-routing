<?php

namespace App\Actions\V2;

use Illuminate\Http\Exceptions\HttpResponseException;

class GetDirectionsFromOSRMAction 
{
    public function execute($pickup, $dropoff)
    {
        $uri = "route/v1/driving/".$pickup.";".$dropoff.".json?steps=true";

        $ch = curl_init();
        if (!$ch)
        {
            throw new HttpResponseException(response()->json([
                'message' => 'Failed to initialize a cURL session',
            ], 500));
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_URL,"http://router.project-osrm.org/" . $uri);
        curl_setopt($ch, CURLOPT_URL,"http://localhost:5000/" . $uri);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode != 200)
        {
            throw new HttpResponseException(response()->json([
                'message' => 'HTTP status code: ' . $httpCode,
            ], $httpCode));
        }

        if (curl_errno($ch) == 28)
        {
            throw new HttpResponseException(response()->json([
                'message' => 'Timeout',
            ], 500));
        }
        curl_close($ch);

        return json_decode($response, true);
    }
}