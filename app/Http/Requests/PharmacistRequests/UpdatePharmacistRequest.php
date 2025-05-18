<?php

namespace App\Http\Requests\PharmacistRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePharmacistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name'  => 'sometimes|string|max:255',
            'username'   => 'sometimes|string|unique:pharmacists',
            'password'   => 'sometimes|string|min:8',
            'phone'      => 'sometimes|string|unique:pharmacists',
            'salary'     => 'sometimes|int|min:0'
        ];
    }
}
