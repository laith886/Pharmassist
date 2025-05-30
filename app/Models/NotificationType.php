<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $guarded=['id'];

    public function notifications() {
        return $this->hasMany(Notification::class);
    }
}
