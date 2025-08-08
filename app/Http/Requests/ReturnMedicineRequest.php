<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnMedicineRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'sale_id' => 'required|exists:sales,id',
            'sale_item_id' => 'required|exists:sale_items,id',
            'quantity_returned' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ];
    }
}
