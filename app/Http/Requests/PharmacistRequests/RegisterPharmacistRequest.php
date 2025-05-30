<?php

namespace App\Http\Requests\PharmacistRequests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPharmacistRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'username'   => 'required|string|unique:pharmacists',
            'password'   => 'required|string|min:8',
            'phone'      => 'required|string|unique:pharmacists',
            'salary'     => 'required|int|min:0',
            'is_admin'=>'required|boolean'
        ];
    }
}
