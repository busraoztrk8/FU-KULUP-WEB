<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\Translatable;
    
    protected $fillable = [
        'title',
        'title_en',
        'slug',
        'content',
        'content_en',
        'image_path',
        'club_id',
        'is_published',
        'published_at',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
    
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
}
