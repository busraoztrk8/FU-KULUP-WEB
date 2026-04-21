<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'label', 'url', 'location', 'target', 'order', 'is_active', 'show_in_footer', 'parent_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_footer' => 'boolean'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeMain($query)
    {
        return $query->where('location', 'main')->whereNull('parent_id');
    }
}
