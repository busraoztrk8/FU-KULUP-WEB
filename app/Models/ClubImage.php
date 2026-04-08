<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClubImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'club_id',
        'image_path',
        'sort_order',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
