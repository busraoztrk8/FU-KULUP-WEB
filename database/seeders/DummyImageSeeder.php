<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class DummyImageSeeder extends Seeder
{
    public function run()
    {
        // Kulüpler için rastgele güzel kapak logoları
        $clubImages = [
            'https://images.unsplash.com/photo-1543269865-cbf427effbad?q=80&w=800',
            'https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=800',
            'https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800',
        ];

        $clubs = Club::all();
        foreach ($clubs as $index => $club) {
            $club->update(['logo' => $clubImages[$index % count($clubImages)]]);
        }

        // Etkinlikler için fotoğraflar
        $eventImages = [
            'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=800',
            'https://images.unsplash.com/photo-1540317580384-e5d43867caa6?q=80&w=800',
            'https://images.unsplash.com/photo-1515169067868-5387ec356754?q=80&w=800',
        ];

        $events = Event::all();
        foreach ($events as $index => $event) {
            $event->update(['image' => $eventImages[$index % count($eventImages)]]);
        }

        // Galeri resimleri ekle
        DB::table('gallery_images')->truncate();
        
        $gallery = [
            ['title' => 'Bahar Şenliği', 'image_path' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?q=80&w=800'],
            ['title' => 'Robotik Yarışması', 'image_path' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?q=80&w=800'],
            ['title' => 'Kariyer Fuarı', 'image_path' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800'],
            ['title' => 'Mezuniyet', 'image_path' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800'],
        ];

        foreach ($gallery as $gal) {
            DB::table('gallery_images')->insert([
                'title' => $gal['title'],
                'image_path' => $gal['image_path'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Haberler ve Duyurular için verileri oluştur (Eğer yoksa)
        if (\App\Models\News::count() == 0) {
            \App\Models\News::create([
                'title' => 'Üniversitemizden Büyük Başarı',
                'slug' => 'universitemizden-buyuk-basari',
                'content' => 'Üniversitemiz uluslararası sıralamada üst sıralara yükseldi.',
                'image_path' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800',
                'club_id' => $clubs->first()->id ?? null,
                'is_published' => true,
                'published_at' => now(),
            ]);
        } else {
            foreach(\App\Models\News::all() as $n) {
                $n->update(['image_path' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800']);
            }
        }

        if (\App\Models\Announcement::count() == 0) {
            \App\Models\Announcement::create([
                'title' => 'Bahane Şenlikleri Hakkında',
                'slug' => 'bahar-senlikleri-hakkinda',
                'content' => 'Bahar şenlikleri bu yıl dolu dolu geçecek.',
                'image_path' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?q=80&w=800',
                'club_id' => $clubs->first()->id ?? null,
                'is_published' => true,
                'published_at' => now(),
            ]);
        } else {
            foreach(\App\Models\Announcement::all() as $a) {
                $a->update(['image_path' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?q=80&w=800']);
            }
        }
    }
}
