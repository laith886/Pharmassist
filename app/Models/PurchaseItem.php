<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $guarded=['id'];

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }
}
