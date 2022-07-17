<?php

namespace App\Actions\V2;

use Illuminate\Http\Exceptions\HttpResponseException;
use Jenssegers\Agent\Agent;
use App\Models\AppVersion;

class GetAppVersionAction 
{
    public function execute($type)
    {   
        $appVersion = AppVersion::where([
            ['type', '=', $type],
            ['platform', '=', (new Agent())->platform()],
        ])->first();

        if ($appVersion) {
            return $appVersion->version;
        }

        throw new HttpResponseException(response()->json([
            'message' => 'Version not found.',
        ], 404));
    }
}
