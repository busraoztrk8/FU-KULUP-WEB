<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getVal($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Update or create a setting.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setVal($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
