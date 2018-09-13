<?php

namespace App\Http\Requests\Package;

use App\Rules\Package\AmountLimit;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'order_id' => 'required|exists:orders,id',
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:100',
            'address' => 'required|string',
            'remark' => 'nullable|string',
            'sent_at' => 'nullable|date',
            'arrived_at' => 'required|date',
            'checked' => 'nullable|boolean',
            'cases' => [
                'nullable', 'array', new AmountLimit($this)
            ],
            'cases.*.case_id' => 'required|exists:cases,id',
            'cases.*.amount' => 'nullable|integer'
        ];
    }
}
