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
    "Yapay Zeka Zirvesi",
    "Girisimcilik ve Inovasyon Paneli",
    "Kis Festivali ve Konser",
    "Bahar Senlikleri Lansmani",
    "Kariyer Kampi",
    "Acik Hava Sinemasi",
    "Robotik Yarismasi Hazirliklari",
    "Tiyatro Gosterimi: Hamlet",
    "Uluslararasi Ogrenci Zirvesi",
    "Kampus Temizligi Gonulluleri",
    "Kodlama Atolyesi: Ilk Adim",
    "E-Spor Turnuvasi Finalleri",
    "Satranc Turnuvasi",
    "Geri Donusum Fuari",
    "Munazara Yarismasi"
];

$unsplashImages = [
    "https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800",
    "https://images.unsplash.com/photo-1515169067868-5387ec356754?q=80&w=800",
    "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800",
    "https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800",
    "https://images.unsplash.com/photo-1528605248644-14dd04022da1?q=80&w=800",
];

foreach ($eventTitles as $i => $title) {
    $club = $clubs->random();
    $cat = $categories->random();
    
    // Spread dates: some past, mostly future
    $daysOffset = rand(-10, 30);
    $start = now()->addDays($daysOffset)->setHour(rand(9, 16))->setMinute(0);
    $end = clone $start;
    $end->addHours(rand(2, 5));

    Event::create([
        "title" => $title,
        "slug" => Str::slug($title) . "-" . rand(1000, 9999),
        "description" => "Harika bir deneyim sizleri bekliyor. Katilmak icin kayit olun.",
        "short_description" => "Harika bir etkinlik.",
        "start_time" => $start,
        "end_time" => $end,
        "location" => "Amfi " . rand(1, 10),
        "club_id" => $club->id,
        "category_id" => $cat->id,
        "image" => $unsplashImages[$i % count($unsplashImages)],
        "max_participants" => rand(50, 500),
        "current_participants" => rand(10, 45),
        "status" => "published",
        "is_featured" => ($i < 3) ? true : false,
    ]);
}

echo "15 test events created successfully.\n";

