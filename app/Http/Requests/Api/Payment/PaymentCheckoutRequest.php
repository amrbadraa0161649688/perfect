<?php

namespace App\Http\Requests\Api\Payment;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'paymethod' => ['required', 'string', 'in:mada,card'],
            'amount' => ['required', 'numeric'],
            'order_id' => ['required', 'exists:waybill_hd,waybill_id'],
        ];
    }
}
