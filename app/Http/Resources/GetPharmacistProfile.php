<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPharmacistProfile extends JsonResource
{

    public function toArray(Request $request): array
    {
         return
            [
                'first_name' => $this->first_name,
                'last_name'  => $this->last_name,
                'user_name'  => $this->username,
                'phone'      => $this->phone,
                'salary'     => $this->salary,
                'is_admin'   => $this->is_admin
            ];

    }
}
