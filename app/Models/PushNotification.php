<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = ['title', 'message', 'topic_id', 'audience', 'url', 'post_id', 'send_at', 'sent_at', 'created_by'];
    protected $casts = ['send_at' => 'datetime', 'sent_at' => 'datetime'];

    public function topic()
    {
        return $this->belongsTo(NotificationTopic::class, 'topic_id');
    }

    public function deliveries()
    {
        return $this->hasMany(NotificationDelivery::class);
    }

    public function deliveredCount(): int
    {
        return $this->deliveries()->whereIn('status', ['delivered', 'opened'])->count();
    }

    public function openedCount(): int
    {
        return $this->deliveries()->where('status', 'opened')->count();
    }
}
