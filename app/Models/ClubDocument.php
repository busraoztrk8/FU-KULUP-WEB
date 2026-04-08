<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
