<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'order_id' => 'sometimes|required|exists:orders,id',
            'name' => 'sometimes|required|string|max:100',
            'phone' => 'nullable|string|max:100',
            'address' => 'sometimes|required|string',
            'remark' => 'nullable|string',
            'sent_at' => 'nullable|date',
            'arrived_at' => 'sometimes|required|date',
            'checked' => 'nullable|boolean',
            'cases' => 'nullable|array',
            'cases.*.case_id' => 'required|exists:cases,id',
            'cases.*.amount' => 'nullable|integer'
        ];
    }
}
