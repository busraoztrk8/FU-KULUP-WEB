<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgram extends Model
{
    use HasFactory;

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
