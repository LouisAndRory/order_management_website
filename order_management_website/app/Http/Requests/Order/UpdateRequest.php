<?php

namespace App\Http\Requests\Order;

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
            'name' => 'sometimes|required|string|max:100',
            'name_backup' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:100',
            'phone_backup' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:50',
            'deposit' => 'nullable|integer',
            'extra_fee' => 'nullable|integer',
            'final_paid' => 'nullable|boolean',
            'engaged_date' => 'nullable|date',
            'married_date' => 'nullable|date',
            'remark' => 'nullable|string',
            'card_required' => 'nullable|boolean',
            'wood_required' => 'nullable|boolean',
            'cases' => 'nullable|array',
            'cases.*.case_type_id' => 'nullable|exists:case_types,id',
            'cases.*.price' => 'nullable|integer',
            'cases.*.amount' => 'nullable|integer',
            'cases.*.cookies' => 'nullable|array',
            'cases.*.cookies.*.cookie_id' => 'sometimes|required|exists:cookies,id',
            'cases.*.cookies.*.pack_id' => 'sometimes|required|exists:packs,id',
            'cases.*.cookies.*.amount' => 'nullable|integer'
        ];
    }
}
