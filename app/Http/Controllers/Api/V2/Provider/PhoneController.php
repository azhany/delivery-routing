<?php

namespace App\Http\Controllers\Api\V2\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\V2\Provider\Phone\SendRequest;
use App\Http\Requests\V2\Phone\VerifyRequest;
use App\Actions\V2\Phone\DeletePhoneVerifyIfExistAction;
use App\Actions\V2\Phone\StorePhoneVerifyAction;
use App\Actions\V2\Phone\UpdatePhoneVerifyAction;
use App\Actions\V2\Phone\CheckCodeAction;
use App\Actions\V2\Provider\StoreProviderAction;
use App\Actions\V2\Provider\Clan\StoreClanMemberAction;
use App\Jobs\V2\ProcessPhoneVerifyCode;
use App\Jobs\V2\ProcessEmailVerify;

class PhoneController extends Controller
{
    public function send(SendRequest $request)
    {
        (new DeletePhoneVerifyIfExistAction)->execute($request->unverifyAccount->phoneVerify);

        $phoneVerify = (new StorePhoneVerifyAction)->execute($request->unverifyAccount->phoneVerify(), $request->all());

        ProcessPhoneVerifyCode::dispatch($phoneVerify);

        return response()->json([
            'message' => 'Successfully send OTP.',
            'data' => [
                'request_duration' => $phoneVerify->request_duration
            ]
        ], 201);
    }

    public function resend(Request $request)
    {
        $phoneVerify = (new UpdatePhoneVerifyAction)->execute($request->phoneVerify);

        ProcessPhoneVerifyCode::dispatch($phoneVerify);

        return response()->json([
            'message' => 'Successfully resend OTP.',
            'data' => [
                'request_duration' => $phoneVerify->request_duration
            ]
        ], 200);
    }

    public function verify(VerifyRequest $request)
    {
        (new CheckCodeAction)->execute($request->phoneVerify, $request->code);

        DB::beginTransaction();

        try {
            $user = (new StoreProviderAction)->execute($request->unverifyAccount, $request->phoneVerify->phone_code, $request->phoneVerify->phone_number);

            $accessToken = $user->createToken('Snapx', ['provider'])->accessToken;

            $user->wallet()->create();

            (new StoreClanMemberAction)->execute($user);

            $request->phoneVerify->delete();

            $request->unverifyAccount->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            throw new HttpResponseException(response()->json([
                'message' => 'Server error.',
            ], 500));
        }

        ProcessEmailVerify::dispatch($user);

        return response()->json([
            'message' => 'Successfully verified.',
            'data' => [
                'token' => $accessToken
            ]
        ], 200);
    }
}
