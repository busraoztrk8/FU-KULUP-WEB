<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\Menu::updateOrCreate(
    ['label' => 'Duyurular'],
    [
        'url' => '/etkinlikler#duyurular-sec',
        'order' => 4,
        'is_active' => true,
    ]
);

echo "Duyurular menu added successfully.\n";
