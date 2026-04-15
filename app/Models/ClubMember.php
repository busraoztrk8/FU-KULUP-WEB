<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClubMember extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['club_id', 'user_id', 'status', 'message', 'title', 'approved_at'];

    protected $casts = ['approved_at' => 'datetime'];

    public function club() { return $this->belongsTo(Club::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function registrationData()
    {
        return $this->hasMany(ClubRegistrationData::class);
    }
}
