<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestedMedicine extends Model
{
    protected $guarded=['id'];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }

}
