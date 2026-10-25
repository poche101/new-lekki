<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'banner_label',
        'link',
        'image',
        'video',
        'status',
        'starts_on',
        'ends_on',
        'impressions',
        'clicks',
    ];

    protected $casts = ['starts_on' => 'date', 'ends_on' => 'date'];

    public function scopeActive($q)
    {
        return $q->where('status', 'active')
            ->where(fn ($q2) => $q2->whereNull('starts_on')->orWhere('starts_on', '<=', today()))
            ->where(fn ($q2) => $q2->whereNull('ends_on')->orWhere('ends_on', '>=', today()));
    }

    public function clickThroughRate(): string
    {
        return $this->impressions ? round($this->clicks / $this->impressions * 100, 1) . '%' : '—';
    }

    /** Public URL of the uploaded image, or null. */
    public function imageUrl(): ?string
    {
        return $this->mediaUrl($this->image);
    }

    /** Public URL of the uploaded video, or null. */
    public function videoUrl(): ?string
    {
        return $this->mediaUrl($this->video);
    }

    private function mediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return preg_match('#^https?://#i', $path)
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    }
}
