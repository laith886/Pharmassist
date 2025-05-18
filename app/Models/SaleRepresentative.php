<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleRepresentative extends Model
{
    protected $guarded=['id'];

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
