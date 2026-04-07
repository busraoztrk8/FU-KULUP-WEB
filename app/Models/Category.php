<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'icon', 'color'];

    /**
     * Set the category name and slug.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get the clubs associated with the category.
     */
    public function clubs()
    {
        return $this->hasMany(Club::class);
    }

    /**
     * Get the events associated with the category.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
