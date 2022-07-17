<?php

namespace App\Http\Controllers\Api\V2\Customer;

use App\Http\Controllers\Controller;
use App\Actions\V2\GetAppVersionAction;

class AppVersionController extends Controller
{
    public function __invoke()
    {   
        return response()->json([
            'data' => [
                'version' => (new GetAppVersionAction)->execute('customer')
            ]
        ], 200);
    }
}