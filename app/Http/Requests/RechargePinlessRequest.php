<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechargePinlessRequest extends FormRequest
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
            'amount' => ['required'],
            'netone_number' => ['required'],
            'payment_method' => ['required', 'in:ecocash,stripe'],
            'ecocash_number' => ['required_if:payment_method,ecocash']
        ];
    }
}
