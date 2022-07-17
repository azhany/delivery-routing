<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Actions\V2\RaudhahPayChecksumAction;
use App\Actions\V2\UpdateRaudhahPayAction;
use App\Actions\V2\Wallet\UpdateTopupAction;

class RaudhahPayController extends Controller
{
    public function callback(Request $request)
    {
        (new RaudhahPayChecksumAction)->execute($request->all());

        DB::beginTransaction();

        try {
            $raudhahPay = (new UpdateRaudhahPayAction)->execute($request->all());

            if ($raudhahPay->reference_2 == 'Topup') {
                (new UpdateTopupAction($raudhahPay->topup))->execute();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw new HttpResponseException(response()->json([
                'message' => 'Server error.',
            ], 500));
        }

        return response()->json([
            'message' => 'Successfully.',
        ], 200);
    }

    public function redirect()
    {
        return response()->noContent();
    }
}
