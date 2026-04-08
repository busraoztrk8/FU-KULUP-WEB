<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Category;
use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole   = Role::create(['name' => 'admin',   'label' => 'Yönetici']);
        $editorRole  = Role::create(['name' => 'editor',  'label' => 'Kulüp Başkanı']);
        $studentRole = Role::create(['name' => 'student', 'label' => 'Öğrenci']);

        // Categories
        $cats = [
            ['name' => 'Teknoloji',    'icon' => 'hub',           'color' => '#3b82f6'],
            ['name' => 'Sanat',        'icon' => 'palette',       'color' => '#db2777'],
            ['name' => 'Spor',         'icon' => 'fitness_center','color' => '#2563eb'],
            ['name' => 'Girişimcilik', 'icon' => 'lightbulb',     'color' => '#f59e0b'],
            ['name' => 'Kariyer',      'icon' => 'work',          'color' => '#10b981'],
            ['name' => 'Akademik',     'icon' => 'school',        'color' => '#475569'],
        ];
        foreach ($cats as $cat) {
            Category::create($cat);
        }

        // Admin user
        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@firat.edu.tr',
            'password'          => Hash::make('password123'),
            'role_id'           => $adminRole->id,
            'email_verified_at' => now(),
        ]);

        // Editor user
        $editor = User::create([
            'name'              => 'Ali Yıldız',
            'email'             => 'editor@firat.edu.tr',
            'password'          => Hash::make('password123'),
            'role_id'           => $editorRole->id,
            'email_verified_at' => now(),
        ]);

        // Sample clubs
        $techCat = Category::where('name', 'Teknoloji')->first();
        $artCat  = Category::where('name', 'Sanat')->first();

        $club1 = Club::create([
            'name'              => 'Robotik ve Yapay Zeka Kulübü',
            'slug'              => 'robotik-ve-yapay-zeka-kulubu',
            'description'       => 'Robotik ve yapay zeka alanında projeler geliştiren öğrenci kulübü.',
            'short_description' => 'Robotik ve YZ projeleri',
            'category_id'       => $techCat->id,
            'president_id'      => $editor->id,
            'member_count'      => 120,
            'is_active'         => true,
        ]);

        $club2 = Club::create([
            'name'              => 'Modern Sanat Atölyesi',
            'slug'              => 'modern-sanat-atolyesi',
            'description'       => 'Resim, heykel ve dijital sanat alanlarında çalışmalar yapan kulüp.',
            'short_description' => 'Sanat ve yaratıcılık',
            'category_id'       => $artCat->id,
            'member_count'      => 85,
            'is_active'         => true,
        ]);

        // Update editor's club
        $editor->update(['club_id' => $club1->id]);

        // Sample events
        $akademikCat = Category::where('name', 'Akademik')->first();

        Event::create([
            'title'            => 'Geleceğin Teknolojileri Zirvesi',
            'slug'             => 'gelecegin-teknolojileri-zirvesi',
            'description'      => 'Yapay zeka, robotik ve gelecek teknolojileri üzerine kapsamlı bir zirve.',
            'short_description'=> 'Teknoloji zirvesi',
            'start_time'       => now()->addDays(15),
            'end_time'         => now()->addDays(15)->addHours(4),
            'location'         => 'Mühendislik Fakültesi Konferans Salonu',
            'club_id'          => $club1->id,
            'category_id'      => $akademikCat->id,
            'max_participants'  => 200,
            'current_participants' => 142,
            'status'           => 'published',
            'is_featured'      => true,
        ]);

        Event::create([
            'title'            => 'Kariyer Günleri 2026',
            'slug'             => 'kariyer-gunleri-2026',
            'description'      => 'Sektör temsilcileriyle buluşma ve kariyer fırsatları etkinliği.',
            'short_description'=> 'Kariyer etkinliği',
            'start_time'       => now()->addDays(22),
            'location'         => 'Kongre Merkezi',
            'club_id'          => $club2->id,
            'max_participants'  => 150,
            'current_participants' => 87,
            'status'           => 'published',
        ]);
        // Default Menus
        \App\Models\Menu::updateOrCreate(['url' => '/'], ['label' => 'Anasayfa', 'order' => 1, 'is_active' => true, 'location' => 'main']);
        \App\Models\Menu::updateOrCreate(['url' => '/kulupler'], ['label' => 'Kulüpler', 'order' => 2, 'is_active' => true, 'location' => 'main']);
        \App\Models\Menu::updateOrCreate(['url' => '/etkinlikler'], ['label' => 'Etkinlikler', 'order' => 3, 'is_active' => true, 'location' => 'main']);
    }
}
