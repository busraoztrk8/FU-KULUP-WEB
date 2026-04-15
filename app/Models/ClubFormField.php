<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'label',
        'type',
        'placeholder',
        'options',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options'     => 'array',
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function registrationData()
    {
        return $this->hasMany(ClubRegistrationData::class);
    }

    /**
     * Bir kulübe varsayılan form alanlarını ekler.
     */
    public static function createDefaultFields(int $clubId): void
    {
        $defaults = [
            ['label' => 'Adınız - Soyadınız', 'type' => 'text', 'placeholder' => 'Yanıtınız', 'is_required' => true, 'sort_order' => 1],
            ['label' => 'Öğrenci Numaranız', 'type' => 'text', 'placeholder' => 'Yanıtınız', 'is_required' => true, 'sort_order' => 2],
            ['label' => 'E-posta Adresiniz', 'type' => 'email', 'placeholder' => 'Yanıtınız', 'is_required' => true, 'sort_order' => 3],
            ['label' => 'Fakülteniz - Bölümünüz', 'type' => 'text', 'placeholder' => 'Yanıtınız', 'is_required' => true, 'sort_order' => 4],
            ['label' => 'Telefon Numaranız', 'type' => 'tel', 'placeholder' => 'Yanıtınız', 'is_required' => true, 'sort_order' => 5],
            ['label' => 'KVKK metnini tam olarak okuduğumu, anladığımı ve onayladığımı kabul, beyan ve taahhüt ederim.', 'type' => 'checkbox', 'placeholder' => null, 'is_required' => true, 'sort_order' => 6],
        ];

        foreach ($defaults as $field) {
            self::create(array_merge($field, ['club_id' => $clubId]));
        }
    }
}
