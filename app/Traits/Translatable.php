<?php

namespace App\Traits;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Str;

trait Translatable
{
    /**
     * Get the translatable attributes for the model.
     * It looks for columns ending with '_en' in the $fillable array
     * and maps them to their base column.
     * Alternatively, models can define a $translatable array.
     */
    public function getTranslatableAttributes(): array
    {
        if (property_exists($this, 'translatable')) {
            return $this->translatable;
        }

        $translatable = [];
        $fillable = $this->getFillable();

        foreach ($fillable as $attribute) {
            if (Str::endsWith($attribute, '_en')) {
                $baseAttribute = substr($attribute, 0, -3);
                if (in_array($baseAttribute, $fillable)) {
                    $translatable[] = $baseAttribute;
                }
            }
        }

        // Add special cases for non-fillable or manually defined ones if needed
        return $translatable;
    }

    /**
     * Override getAttribute to support dynamic locale retrieval.
     */
    public function getAttribute($key)
    {
        $translatable = $this->getTranslatableAttributes();

        // Konsolda isek veya bir model field'ı EN ile bitiyorsa orijinal veriyi döndür.
        if (app()->runningInConsole() || str_ends_with($key, '_en')) {
            return parent::getAttribute($key);
        }

        $isAdmin = request()->is('admin*') || 
                   request()->is('*/admin*') || 
                   request()->segment(1) == 'admin' || 
                   request()->segment(2) == 'admin' || 
                   (request()->route() && str_starts_with(request()->route()->getName(), 'admin.'));

        if (!$isAdmin && in_array($key, $translatable) && app()->getLocale() === 'en') {
            $enKey = $key . '_en';
            $enValue = parent::getAttribute($enKey);
            
            // Eğer İngilizce çevirisi varsa onu döndür, yoksa Türkçe orijinali (fallback)
            if (!empty($enValue)) {
                return $enValue;
            }
        }

        return parent::getAttribute($key);
    }

    /**
     * Boot the trait to auto-translate attributes before saving.
     */
    protected static function bootTranslatable()
    {
        static::saving(function ($model) {
            $translatable = $model->getTranslatableAttributes();
            
            if (empty($translatable)) {
                return;
            }

            try {
                $tr = new GoogleTranslate('en');
                $tr->setSource('tr');

                $isAnyDirty = false;
                foreach ($translatable as $attr) {
                    if ($model->isDirty($attr)) {
                        $isAnyDirty = true;
                        break;
                    }
                }

                foreach ($translatable as $attribute) {
                    $enAttribute = $attribute . '_en';
                    
                    // Çeviri yapılması gereken durumlar:
                    // 1. İngilizce alan boşsa
                    // 2. İngilizce alan Türkçe ile aynıysa (henüz çevrilmemiş fallback)
                    // 3. Türkçe alan değişmişse (isDirty)
                    // 4. Diğer herhangi bir alan değişmişse (senkronizasyon için toplu güncelleme)
                    $shouldTranslate = empty($model->{$enAttribute}) || 
                                      $model->{$enAttribute} === $model->{$attribute} || 
                                      $model->isDirty($attribute) ||
                                      $isAnyDirty;

                    if (!empty($model->{$attribute}) && $shouldTranslate) {
                        $model->{$enAttribute} = $tr->translate($model->{$attribute});
                    }
                }
            } catch (\Exception $e) {
                // Çeviri servisi hata verirse sessizce devam et
                \Illuminate\Support\Facades\Log::error('Auto-translation failed: ' . $e->getMessage());
            }
        });
    }
}
