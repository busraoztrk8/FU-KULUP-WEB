<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SettingController extends Controller
{
    /**
     * Otomatik çeviri yapılacak alanlar (Türkçe key => İngilizce key)
     */
    private $translatableKeys = [
        'announcements_hero_title' => 'announcements_hero_title_en',
        'announcements_hero_subtitle' => 'announcements_hero_subtitle_en',
        'site_name' => 'site_name_en',
        'site_description' => 'site_description_en',
    ];

    public function update(Request $request)
    {
        $settings = $request->except('_token');

        foreach ($settings as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $value = $filename;
            }
            SiteSetting::setVal($key, $value);

            // Otomatik çeviri: Türkçe alan kaydedildiğinde İngilizce çevirisini de oluştur
            if (isset($this->translatableKeys[$key]) && !empty(trim($value ?? ''))) {
                try {
                    $translator = new GoogleTranslate('en');
                    $translator->setSource('tr');
                    $translated = $translator->translate($value);
                    if ($translated) {
                        SiteSetting::setVal($this->translatableKeys[$key], $translated);
                    }
                } catch (\Exception $e) {
                    // Çeviri başarısız olursa sessizce devam et
                    \Log::warning('Otomatik çeviri başarısız: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
