<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V2\ValidateLocationRequest;
use Redis;

class ActiveStatusLocationController extends Controller
{
    /**
     * Set provider online/offline status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        Redis::set('provider:status:' . $id, $request->status);

        return response()->json([
            'status' => true,
            'data' => "Success."
        ], 200);
    }

    /**
     * Get provider online/offline status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStatus($id)
    {
        if(!Redis::exists('provider:status:' . $id)) {
            throw new HttpResponseException(response()->json([
                'message' => 'There is no provider status set previously.',
            ], 404));
        }

        $status = Redis::get('provider:status:' . $id);

        return response()->json([
            'status' => true,
            'data' => $status
        ], 200);
    }

    /**
     * Set provider current location.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLocation(ValidateLocationRequest $request, $id)
    {
        $locationLatLng = explode(",", $request->location);
        Redis::executeRaw('GEOADD provider:location ' . implode(" ", $locationLatLng) . ' "' . $id . '"');

        return response()->json([
            'status' => true,
            'data' => "Success."
        ], 200);
    }

    /**
     * Get provider current location.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getLocation($id)
    {
        $location = Redis::executeRaw('GEOPOS provider:location ' . $id);

        return response()->json([
            'status' => true,
            'data' => $location
        ], 200);
    }
}
