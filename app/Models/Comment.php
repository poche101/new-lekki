<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // No status/approval field on purpose — see migration notes. Comments post
    // immediately; admins with the `moderate` permission can delete any of them.
    protected $fillable = ['post_id', 'user_id', 'guest_name', 'body', 'is_admin_reply', 'deleted_by'];
    protected $casts = ['is_admin_reply' => 'boolean'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authorName(): string
    {
        return $this->user->name ?? $this->guest_name ?? 'Guest';
    }
}
