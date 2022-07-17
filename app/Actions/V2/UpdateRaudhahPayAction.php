<?php

namespace App\Actions\V2;

use App\Models\RaudhahPay;

class UpdateRaudhahPayAction 
{
    public function execute($data)
    {
        $raudhahPay = RaudhahPay::where([
            'bill_id' => $data['bill_id'], 
            'bill_no' => $data['bill_no'], 
            'reference_2' => $data['ref2']
        ])->first();

        $raudhahPay->update([
            'reference_id' => $data['ref_id'],
            'payment_method' => $data['payment_method'],
            'is_paid' => $data['paid'],
        ]);

        return $raudhahPay;
    }
}