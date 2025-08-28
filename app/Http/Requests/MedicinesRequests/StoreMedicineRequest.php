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
            'manufacturer' => 'required|string|exists:manufacturers,company_name',
            'categories' => 'required|array|min:1',
            'categories.*' => 'string|exists:categories,category_name',
            'prescription'=>'string',
            'production_Date'=>'required|Date',
            'expiration_Date'=>'required|date|after_or_equal:production_date',
            'quantity_in_stock'=>'required|integer|min:0',
            'sci_name'=>'required|string',
            'price' => 'required|numeric|min:0',
            'minimum_quantity'=>'required|numeric|min:0'
        ];
    }
}
