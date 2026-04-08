<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Fırat Üniversitesi',
            'site_description' => 'Fırat Üniversitesi Akıllı Kulüp ve Etkinlik Platformu',
            'contact_email' => 'destek@firat.edu.tr',
            'contact_phone' => '+90 (424) 237 00 00',
            'social_instagram' => 'firatuniv',
            'social_twitter' => 'firatuniversite',
            'social_facebook' => 'firatuniversitesi',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
