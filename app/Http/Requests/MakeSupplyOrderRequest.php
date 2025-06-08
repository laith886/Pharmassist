<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeSupplyOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {
        return [
            'sale_representative_id' => 'required|integer|exists:sale_representatives,id',

            'items' => 'required|array|min:1',

            'items.*.medicine_id' => 'required|exists:medicines,id',

            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}
