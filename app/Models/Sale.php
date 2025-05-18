<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded=['id'];

    public function pharmacist() {
        return $this->belongsTo(Pharmacist::class);
    }

    public function salesItems() {
        return $this->hasMany(SaleItem::class);
    }

}
