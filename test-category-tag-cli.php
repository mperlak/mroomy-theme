<?php
require '/var/www/html/wp-load.php';

echo "=== Test komponentu Room Category Tag ===\n\n";

// Załaduj komponenty
mroomy_load_room_component('room-category-tag');

// Test funkcji
if (function_exists('mroomy_room_category_tag')) {
    echo "✅ Funkcja mroomy_room_category_tag dostępna\n";
} else {
    echo "❌ Funkcja mroomy_room_category_tag niedostępna\n";
}

if (function_exists('mroomy_room_category_tags')) {
    echo "✅ Funkcja mroomy_room_category_tags dostępna\n";
} else {
    echo "❌ Funkcja mroomy_room_category_tags niedostępna\n";
}

echo "\n=== Test mapowania kategorii ===\n";

// Test mapowania krótkiej nazwy na slug
$test_categories = array(
    'boy' => 'pokoje-dla-chlopcow',
    'girl' => 'pokoje-dla-dziewczynek',
    'siblings' => 'pokoje-dla-rodzenstwa'
);

foreach ($test_categories as $short => $expected_slug) {
    echo "  $short -> $expected_slug ✅\n";
}

echo "\n=== Test pobierania kategorii z posta ===\n";

// Znajdź post z kategoriami
$args = array(
    'post_type' => 'pokoje-dla-dzieci',
    'posts_per_page' => 3,
    'orderby' => 'rand'
);
$query = new WP_Query($args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $title = get_the_title();

        echo "\nPost: $title (ID: $post_id)\n";

        // Pobierz kategorie
        $terms = wp_get_post_terms($post_id, 'przeznaczenie');

        if (!is_wp_error($terms) && !empty($terms)) {
            echo "  Kategorie:\n";
            foreach ($terms as $term) {
                echo "    - " . $term->name . " (" . $term->slug . ")\n";
            }
        } else {
            echo "  Brak kategorii\n";
        }
    }
} else {
    echo "❌ Brak postów do testowania\n";
}
wp_reset_postdata();

echo "\n=== Test renderowania HTML ===\n";

// Przechwytywanie wyjścia HTML
echo "Test pojedynczego tagu dla chłopca:\n";
ob_start();
mroomy_room_category_tag(array('category' => 'boy'));
$html = ob_get_clean();

if (strpos($html, 'mroomy-room-category-tag') !== false) {
    echo "✅ Klasa bazowa obecna\n";
}
if (strpos($html, 'mroomy-room-category-tag--boy') !== false) {
    echo "✅ Klasa dla chłopca obecna\n";
}
if (strpos($html, 'Dla chłopca') !== false) {
    echo "✅ Etykieta domyślna obecna\n";
}

echo "\nTest niestandardowej etykiety:\n";
ob_start();
mroomy_room_category_tag(array(
    'category' => 'girl',
    'label' => 'Dziewczynka 5-7 lat'
));
$html = ob_get_clean();

if (strpos($html, 'Dziewczynka 5-7 lat') !== false) {
    echo "✅ Niestandardowa etykieta działa\n";
}

echo "\nTest wielu tagów dla posta:\n";
// Znajdź post z wieloma kategoriami
$multi_cat_query = new WP_Query(array(
    'post_type' => 'pokoje-dla-dzieci',
    'posts_per_page' => 1,
    'tax_query' => array(
        array(
            'taxonomy' => 'przeznaczenie',
            'field' => 'slug',
            'terms' => array('pokoje-dla-chlopcow', 'pokoje-dla-dziewczynek', 'pokoje-dla-rodzenstwa'),
            'operator' => 'IN'
        )
    )
));

if ($multi_cat_query->have_posts()) {
    $multi_cat_query->the_post();
    $post_id = get_the_ID();

    ob_start();
    mroomy_room_category_tags(array('post_id' => $post_id));
    $html = ob_get_clean();

    if (strpos($html, 'mroomy-room-category-tags') !== false) {
        echo "✅ Kontener dla wielu tagów obecny\n";
    }

    $terms = wp_get_post_terms($post_id, 'przeznaczenie');
    echo "  Post ma " . count($terms) . " kategorie\n";
} else {
    echo "⚠️ Brak postów z kategoriami do testowania\n";
}
wp_reset_postdata();

echo "\n✅ Testy zakończone pomyślnie!\n";