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

        // Admin panelinde veya konsolda isek otomatik çeviri ile maskeleme yapma. 
        // Admin orijinal veriyi görmeli.
        if (app()->runningInConsole() || request()->is('*admin*') || request()->routeIs('admin.*')) {
            return parent::getAttribute($key);
        }

        if (in_array($key, $translatable) && app()->getLocale() === 'en') {
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

                foreach ($translatable as $attribute) {
                    $enAttribute = $attribute . '_en';
                    
                    // Eğer ana özellik (tr) değiştiyse veya İngilizce versiyon boşsa
                    if (!empty($model->{$attribute}) && empty($model->{$enAttribute})) {
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
