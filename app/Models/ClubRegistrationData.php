<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubRegistrationData extends Model
{
    use HasFactory;

    protected $table = 'club_registration_data';

    protected $fillable = [
        'club_member_id',
        'club_form_field_id',
        'value',
    ];

    public function clubMember()
    {
        return $this->belongsTo(ClubMember::class);
    }

    public function formField()
    {
        return $this->belongsTo(ClubFormField::class, 'club_form_field_id');
    }
}
