<?php

namespace App\Http\Requests\MedicinesRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name'=>'required|string',
            'manufacturer_id'=>'required|exists:manufacturers,id',
            'category_id'=>'required|exists:categories,id',
            'prescription'=>'string',
            'production_date'=>'required|Date',
            'expiration_date'=>'required|date|after_or_equal:production_date',
            'quantity_in_stock'=>'required|integer|min:0',
            'barcode'=>'required|string',
            'sci_name'=>'required|string',
            'price' => 'required|numeric|min:0',
            'minimum_quantity'=>'required|numeric|min:0'
        ];
    }
}
