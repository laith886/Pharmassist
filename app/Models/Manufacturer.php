<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $guarded=['id'];
    public function medicines() {
        return $this->hasMany(Medicine::class);
    }

}
