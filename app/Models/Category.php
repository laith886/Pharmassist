<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded=['id'];


    public function medicines() {
        return $this->belongsToMany(Medicine::class, 'medicine_categories');
    }

}
