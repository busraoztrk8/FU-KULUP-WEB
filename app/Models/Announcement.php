<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'content',
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
