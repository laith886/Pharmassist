<?php

namespace App\Http\Requests;

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


     public function rules(): array
    {
        // إذا المسار /pharmacists/{id}
        $id = $this->route('id') ?? $this->route('pharmacist');

        return [
            'first_name'       => 'sometimes|string|max:100',
            'last_name'        => 'sometimes|string|max:100',
            'username'         => 'sometimes|string|max:100|unique:pharmacists,username,' . $id,
            'password'         => 'sometimes|nullable|string|min:8|confirmed',
            'phone'            => 'sometimes|string|max:50',
            'employment_date'  => 'sometimes|date',
            'salary'           => 'sometimes|numeric|min:0',
            'is_admin'         => 'sometimes|boolean',
        ];
    }
}
