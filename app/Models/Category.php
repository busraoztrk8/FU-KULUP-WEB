<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\Translatable;

    protected $fillable = ['name', 'name_en', 'slug', 'icon', 'color', 'is_active'];

    /**
     * Set the category name and slug.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . substr(md5(Str::uuid()), 0, 8);
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
