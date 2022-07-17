<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use App\Actions\V2\GetAppVersionAction;

class AppVersionController extends Controller
{
    public function __invoke()
    {   
        return response()->json([
            'data' => [
                'version' => (new GetAppVersionAction)->execute('provider')
            ]
        ], 200);
    }
}