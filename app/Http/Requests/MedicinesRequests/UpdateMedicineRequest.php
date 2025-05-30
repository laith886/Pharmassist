<?php

namespace App\Http\Requests\MedicinesRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'manufacturer_id' => 'sometimes|exists:manufacturers,id',
            'category_id' => 'sometimes|exists:categories,id',
            'prescription' => 'sometimes|nullable|string',
            'production_date' => 'sometimes|date',
            'expiration_date' => 'sometimes|date|after_or_equal:production_date',
            'quantity_in_stock' => 'sometimes|integer|min:0',
            'barcode' => 'sometimes|string',
            'sci_name' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'minimum_quantity'=>'sometimes|numeric|min:0'
        ];
    }
}
