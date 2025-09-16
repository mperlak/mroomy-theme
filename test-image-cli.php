<?php
require '/var/www/html/wp-load.php';

echo "=== Test komponentu Image ===\n\n";

// Załaduj komponent
mroomy_load_room_component('image');

if (function_exists('mroomy_room_image')) {
    echo "✅ Funkcja mroomy_room_image dostępna\n";
} else {
    echo "❌ Funkcja mroomy_room_image niedostępna\n";
}

// Test parsowania tytułu
echo "\n=== Test parsowania tytułu ===\n";
$test_titles = array(
    'Projekt biało-żółtego pokoju dla chłopca, Jasia (6 lat) (#1234)',
    'Projekt pokoju dla dziewczynki, Zuzi (8 lat)',
    'Projekt pokoju dla Adama (10 lat) (#567)',
);

foreach ($test_titles as $title) {
    $parsed = mroomy_parse_room_title($title);
    echo "\nOryginalny: " . $title . "\n";
    echo "  Główna część: " . $parsed['main'] . "\n";
    echo "  Beneficjent: " . $parsed['beneficiary'] . "\n";
}

// Sprawdź rozmiary obrazków
echo "\n=== Zarejestrowane rozmiary obrazków ===\n";
global $_wp_additional_image_sizes;
$sizes_to_check = array('room-tile-large', 'room-tile-medium', 'room-tile-small');

foreach ($sizes_to_check as $size) {
    if (isset($_wp_additional_image_sizes[$size])) {
        $size_data = $_wp_additional_image_sizes[$size];
        echo "✅ " . $size . ": " . $size_data['width'] . 'x' . $size_data['height'] . "\n";
    } else {
        echo "❌ " . $size . " nie jest zarejestrowany\n";
    }
}

echo "\n✅ Test zakończony pomyślnie!\n";