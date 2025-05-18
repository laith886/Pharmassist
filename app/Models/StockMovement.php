<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $guarded=['id'];

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }

    public function movement() {
        return $this->belongsTo(Movement::class);
    }

    public function pharmacist() {
        return $this->belongsTo(Pharmacist::class);
    }

}
