<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function update(Request $request)
    {
        $settings = $request->except('_token');

        foreach ($settings as $key => $value) {
            SiteSetting::setVal($key, $value);
        }

        return back()->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
