<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'label', 'url', 'location', 'target', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeMain($query)
    {
        return $query->where('location', 'main');
    }

    public function scopeFooter($query)
    {
        return $query->where('location', 'footer');
    }
}
