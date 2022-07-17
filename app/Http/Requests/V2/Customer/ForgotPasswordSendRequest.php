<?php

namespace App\Http\Requests\V2\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Customer;

class ForgotPasswordSendRequest extends FormRequest
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
            'phone_code' => 'required',
            'phone_number' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->phone_code && $this->phone_number) { 
                if ($customer = Customer::where('phone_code', $this->phone_code)->where('phone_number', $this->phone_number)->first()) {
                    $this->merge(['customer' => $customer]);
                } else {
                    $validator->errors()->add('phone_number', 'The selected phone number is invalid.');
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
