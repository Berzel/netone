<?php

namespace App\Http\Requests;

use App\Rules\ValidEcoCashNumber;
use App\Rules\ValidMoney;
use App\Rules\ValidNetoneNumber;
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
            'amount' => ['required', 'numeric', new ValidMoney],
            'payment_method' => ['required', 'in:ecocash'],
            'netone_number' => ['required', new ValidNetoneNumber],
            'ecocash_number' => ['required_if:payment_method,ecocash', new ValidEcoCashNumber]
        ];
    }
}
