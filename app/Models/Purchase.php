<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded=['id'];

    public function pharmacist() {
        return $this->belongsTo(Pharmacist::class);
    }

    public function salesRepresentative() {
        return $this->belongsTo(SaleRepresentative::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchaseItems() {
        return $this->hasMany(PurchaseItem::class);
    }

}
