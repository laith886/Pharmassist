<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAallCategories extends JsonResource
{

    public function toArray(Request $request): array
    {
        return[
            "id"=>$this->id,
            "category_name"=>$this->category_name,
        ];
    }
}
