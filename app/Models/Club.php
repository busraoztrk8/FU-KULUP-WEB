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
        'website_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'facebook_url',
        'mission',
        'vision',
        'founder_name',
        'established_year',
    ];

    /**
     * Get the images for the club gallery.
     */
    public function images()
    {
        return $this->hasMany(ClubImage::class);
    }

    /**
     * Set the club name and slug.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . substr(md5(Str::uuid()), 0, 8);
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

    public function members()
    {
        return $this->hasMany(ClubMember::class);
    }

    public function approvedMembers()
    {
        return $this->hasMany(ClubMember::class)->where('status', 'approved');
    }

    /**
     * Get the documents for the club.
     */
    public function documents()
    {
        return $this->hasMany(ClubDocument::class);
    }
}
