<?php

namespace App\Http\Requests\V2\Provider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Provider;

class CreateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'email' => 'required|email|unique:providers',
            'referral_code' => 'nullable',
            'password' => 'required|min:8',
            'confirm_password' => 'same:password',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->referral_code) {
                if ($referredBy = Provider::where('referral_code', $this->referral_code)->first('id')) {
                    $this->merge(['referred_by' => $referredBy->id]);
                } else {
                    $validator->errors()->add('referral_code', 'The selected referral code is invalid.');
                }
            }
            
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid.',
            'data' => [
                'errors' => $validator->errors(),
            ]
        ], 422));
    }
}
