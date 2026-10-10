<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationDelivery extends Model
{
    protected $fillable = ['push_notification_id', 'push_subscription_id', 'status', 'delivered_at', 'opened_at'];
    protected $casts = ['delivered_at' => 'datetime', 'opened_at' => 'datetime'];
}
