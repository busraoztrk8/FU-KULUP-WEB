<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Club extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'logo',
        'cover_image',
        'category_id',
        'president_id',
        'member_count',
        'event_count',
        'is_active',
    ];

    /**
     * Set the club name and slug.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get the category associated with the club.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who is the president of the club.
     */
    public function president()
    {
        return $this->belongsTo(User::class, 'president_id');
    }

    /**
     * Get the editors associated with the club.
     */
    public function editors()
    {
        return $this->hasMany(User::class, 'club_id');
    }

    /**
     * Get the events organized by the club.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
