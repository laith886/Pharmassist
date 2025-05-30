<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded=['id'];

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }

    public function notificationType() {
        return $this->belongsTo(NotificationType::class);
    }
}
