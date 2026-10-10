<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'note', 'phone', 'sort_order'];

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }

    public function formattedPhone(): string
    {
        return preg_replace('/^(\+234)(\d{3})(\d{3})(\d{4})$/', '$1 $2 $3 $4', $this->phone) ?: $this->phone;
    }
}
