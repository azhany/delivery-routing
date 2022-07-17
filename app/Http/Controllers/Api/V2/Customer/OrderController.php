<?php

namespace App\Http\Controllers\Api\V2\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V2\ValidatePointsRequest;
use App\Http\Requests\V2\ValidateLocationRequest;
use App\Actions\V2\GetDirectionsFromOSRMAction;
use App\Actions\V2\ThrowErrorIfFareNotExistAction;
use App\Models\Fare;
use Carbon\Carbon;
use Redis;


class OrderController extends Controller
{
    /**
     * Get distance, directions and steps from osrm server - calculate fare (from db) x distance (from osrm server).
     * 
     * Then post fare and steps location (to draw polyline on google map tile in front end) to customer app
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDirections(ValidatePointsRequest $request)
    {
        // received in array so front end can send multiple dropoffs
        $pickups = json_decode($request->pickup, true);
        $dropoffs = json_decode($request->dropoff, true);

        $pickup = "";
        foreach($pickups as $pick)
            $pickup .= $pick['longitude'] . "," . $pick['latitude'] . ";";
        $pickup = rtrim($pickup, ";");

        $dropoff = "";
        foreach($dropoffs as $drop)
            $dropoff .= $drop['longitude'] . "," . $drop['latitude'] . ";";
        $dropoff = rtrim($dropoff, ";");

        $steps = array();
        $result = (new GetDirectionsFromOSRMAction)->execute($pickup, $dropoff);
        foreach($result['routes'] as $index => $route) {
            foreach($route['legs'][0]['steps'] as $step) {
                foreach($step['intersections'] as $intersection) {
                    // array_push($steps, $intersection['location']);
                    $steps[] = array(
                                    'latitude' => $intersection['location'][1],
                                    'longitude' => $intersection['location'][0]
                                );
                }
            }
        }
        
        // different hours different fare settings
        $currentHour = Carbon::now('Asia/Kuala_Lumpur')->format('H:i');
        $fare = Fare::where([['start_hour', '<=', $currentHour],['end_hour', '>=', $currentHour]])->first();
        if($fare != null)
            $fare_price = $fare['price_per_km'] * round(($result['routes'][0]['distance'] / 1000), 1); // convert to km first
        else
            (new ThrowErrorIfFareNotExistAction)->execute($fare);

        return response()->json([
            'status' => true,
            'data' => [
                'steps' => $steps,
                'fare' => $fare_price
            ]
        ], 200);
    }

    /**
     * Finding providers location within radius and post order request to selected provider app.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findProviders(ValidateLocationRequest $request)
    {
        // finding providers location within radius
        // post order request to selected provider app

        // from $request->location customer
        $locationLatLng = explode(",", $request->location);

        $providers = Redis::executeRaw('GEOSEARCH provider:location FROMLONLAT' . implode(" ", $locationLatLng) . 'BYRADIUS 5 km ASC WITHCOORD WITHDIST');

        // check provider status (online/offline)
        if(!Redis::exists('provider:status:' . $id)) {
            throw new HttpResponseException(response()->json([
                'message' => 'There is no provider status set previously.',
            ], 404));
        }

        $status = Redis::get('provider:status:' . $id);

        if($status != "online") {
            throw new HttpResponseException(response()->json([
                'message' => 'Provider offline.',
            ], 404));
        }

        return response()->json([
            'status' => true,
            'data' => [
                'providers' => $providers
            ]
        ], 200);
    }

    /**
     * Get provider response (yes or no) and create order (if response is yes).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function providerResponse(Request $request, $id)
    {
        // get provider response (yes/no)
        // order created (if response is yes)
    }

    /**
     * Get customer order booking (with confirmed pickup and dropoff points).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function customerBooking(ValidatePointsRequest $request, $id)
    {
        /*
        if($request->order_type == "ride") {

        } else if($request->order_type == "delivery") {

        }
        */
    }

    /**
     * Receive order status (accepted, cancelled, pickup, dropoff).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderStatus(Request $request)
    {
        if($request->order_status == "accepted") {
            // 
        } else if($request->order_status == "cancelled") {
            // if order was cancelled by both
        } else if($request->order_status == "pickup") {
            // provider arrived at pickup point
        } else if($request->order_status == "dropoff") {
            // provider arrived at dropoff point
        }
    }

    /**
     * Tracking provider (location and time) [in real-time].
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderTracking($order_id)
    {
        return response()->stream(function () {
            while (true) {
                if (connection_aborted()) {
                    break;
                }
                // get provider active location
                $location = Redis::executeRaw('GEOPOS provider:location ' . $order_id);
                echo "event: ping\n", "data: {$location}", "\n\n";
                ob_flush();
                flush();
                sleep(1);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    /**
     * Finished order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function orderSummary($id)
    {
        // order done
    }
}
