<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventSpeaker;
use App\Models\EventProgram;

class EventDetailDemoSeeder extends Seeder
{
    public function run()
    {
        $event = Event::find(1);
        if (!$event) return;

        // Add Speakers
        $speakers = [
            ['name' => 'Ahmet Yılmaz', 'title' => 'Tech Solutions - CEO'],
            ['name' => 'Dr. Elif Kaya', 'title' => 'Fırat Üniversitesi - Akademisyen'],
            ['name' => 'Büşra Öztürk', 'title' => 'Yazılım Geliştirici'],
        ];

        foreach ($speakers as $s) {
            EventSpeaker::updateOrCreate(
                ['event_id' => $event->id, 'name' => $s['name']],
                ['title' => $s['title']]
            );
        }

        // Add Program
        $program = [
            ['time' => '09:00', 'title' => 'Açılış ve Kayıt', 'location' => 'Ana Fuaye'],
            ['time' => '10:00', 'title' => 'Geleceğin Teknolojileri Semineri', 'location' => 'Amfi 1'],
            ['time' => '12:00', 'title' => 'Öğle Arası ve Network', 'location' => 'Bahçe'],
            ['time' => '14:00', 'title' => 'Atölye Çalışmaları', 'location' => 'Laboratuvar A'],
        ];

        foreach ($program as $p) {
            EventProgram::updateOrCreate(
                ['event_id' => $event->id, 'title' => $p['title']],
                ['time' => $p['time'], 'location' => $p['location']]
            );
        }
    }
}
