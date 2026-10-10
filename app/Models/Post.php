<?php

namespace App\Models;

use App\Support\VideoLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'type', 'title', 'slug', 'excerpt_text', 'body', 'media_item_id', 'author_id',
        'status', 'published_at', 'meta', 'comments_enabled', 'notify_on_publish', 'views',
    ];
    protected $casts = ['meta' => 'array', 'published_at' => 'datetime', 'comments_enabled' => 'boolean', 'notify_on_publish' => 'boolean'];

    /**
     * Makes both directions agree on slug: incoming {post:slug} route binding
     * AND route('blog.show', $post) URL generation. Without this, Laravel
     * would resolve routes by slug but generate links using the numeric id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function (Post $post) {
            if (! $post->slug) {
                $base = Str::slug($post->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . ++$i;
                }
                $post->slug = $slug;
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function mediaItem()
    {
        return $this->belongsTo(MediaItem::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published')->where(function ($q2) {
            $q2->whereNull('published_at')->orWhere('published_at', '<=', now());
        });
    }

    public function scopeType($q, string $type)
    {
        return $q->where('type', $type);
    }

    /** For the homepage: latest published posts of a type, newest first. */
    public function scopeLatestOfType($q, string $type, int $limit = 6)
    {
        return $q->type($type)->published()->latest('published_at')->limit($limit);
    }

    public function meta(string $key, $default = null)
    {
        return data_get($this->meta, $key, $default);
    }

    /** Parsed video for this post (see App\Support\VideoLink), or null if no link was set. */
    public function video(): ?array
    {
        return VideoLink::parse($this->meta('video_url'));
    }

    /** Uploaded thumbnail if there is one, otherwise the video's own poster (YouTube), otherwise null. */
    public function thumbnailUrl(): ?string
    {
        if ($path = $this->meta('thumbnail')) {
            return Storage::disk('public')->url($path);
        }

        return $this->video()['thumb'] ?? null;
    }

    /** Where this post lives on the public site (Higher Life episodes have their own archive). */
    public function publicUrl(): string
    {
        return $this->type === 'higher_life' ? route('higher-life.show', $this) : route('blog.show', $this);
    }

    public function readTime(): string
    {
        if ($mins = $this->meta('read_minutes')) {
            return $mins . ' min read';
        }
        $words = str_word_count(strip_tags((string) $this->body));

        return max(1, (int) ceil($words / 200)) . ' min read';
    }
}
