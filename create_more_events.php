<?php
use App\Models\Event;
use App\Models\Club;
use App\Models\Category;
use Illuminate\Support\Str;

$clubs = Club::all();
$categories = Category::all();

if ($clubs->isEmpty() || $categories->isEmpty()) {
    echo "Lutfen once kulup ve kategorileri ekleyin.\n";
    exit;
}

$eventTitles = [
    "Diksiyon ve Hitabet Egitimi",
    "Gelecegin Muhendisleri",
    "Kitap Okuma Gunleri",
    "Siber Guvenlige Giris",
    "Universite Tanitim Gunu",
    "Web Gelistirme Hackathonu",
    "Kisa Film Gosterimi",
    "Genc Kariyer Soylesisi",
    "Doga Yuruyusu",
    "Muzik Dinletisi Klasikler"
];

$unsplashImages = [
    "https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800",
    "https://images.unsplash.com/photo-1515169067868-5387ec356754?q=80&w=800",
    "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800",
    "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800",
    "https://images.unsplash.com/photo-1528605248644-14dd04022da1?q=80&w=800",
];

foreach ($eventTitles as $i => $title) {
    if (Event::where("title", $title)->exists()) continue;

    $club = $clubs->random();
    $cat = $categories->random();
    
    // Spread dates: some past, mostly future
    $daysOffset = rand(-15, 45);
    $start = now()->addDays($daysOffset)->setHour(rand(9, 16))->setMinute(0);
    $end = clone $start;
    $end->addHours(rand(2, 5));

    Event::create([
        "title" => $title,
        "slug" => Str::slug($title) . "-" . rand(1000, 9999),
        "description" => "Harika bir deneyim sizleri bekliyor. Yepyeni bilgiler edinmek ve sosyal cevrenizi genisletmek icin harika bir firsat.",
        "short_description" => "Kacirilmayacak ozel bir organizasyon.",
        "start_time" => $start,
        "end_time" => $end,
        "location" => "Amfi " . rand(1, 10),
        "club_id" => $club->id,
        "category_id" => $cat->id,
        "image" => $unsplashImages[$i % count($unsplashImages)],
        "max_participants" => rand(50, 200),
        "current_participants" => rand(5, 45),
        "status" => "published",
        "is_featured" => false,
    ]);
}

echo "More test events created successfully.\n";

