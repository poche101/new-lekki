<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = ['title', 'banner_label', 'link', 'status', 'starts_on', 'ends_on', 'impressions', 'clicks'];
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
}
