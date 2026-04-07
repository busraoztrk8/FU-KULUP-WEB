<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubMember extends Model
{
    protected $fillable = ['club_id', 'user_id', 'status', 'message', 'approved_at'];

    protected $casts = ['approved_at' => 'datetime'];

    public function club() { return $this->belongsTo(Club::class); }
    public function user() { return $this->belongsTo(User::class); }
}
