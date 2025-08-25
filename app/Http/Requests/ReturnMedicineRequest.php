<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnMedicineRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

   public function rules()
{
    return [
        'sale_id' => ['required', 'integer', 'exists:sales,id'],

        // عند وجود items[]
        'items' => ['sometimes', 'array', 'min:1'],
        'items.*.sale_item_id' => ['required_with:items', 'integer', 'exists:sale_items,id'],
        'items.*.quantity_returned' => ['required_with:items', 'integer', 'min:1'],


        // عند عدم وجود items[] (الحالة القديمة)
        'sale_item_id' => ['required_without:items', 'integer', 'exists:sale_items,id'],
        'quantity_returned' => ['required_without:items', 'integer', 'min:1'],
        
    ];
}

}
