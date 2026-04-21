<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'time',
        'title',
        'location',
        'order'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
