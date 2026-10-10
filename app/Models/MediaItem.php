<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    protected $fillable = ['title', 'type', 'category', 'file_url', 'thumbnail_url', 'status', 'publish_at', 'views', 'created_by'];
    protected $casts = ['publish_at' => 'datetime'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }
}
