<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pharmacist extends Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $guarded=['id'];
    protected $hidden = ['password'];

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }

    public function stockMovements() {
        return $this->hasMany(StockMovement::class);
    }
}
