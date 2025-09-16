<?php
/**
 * Debug page for rooms components
 * Template Name: Test Debug
 */

get_header();
?>

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Debug - Diagnostyka komponentów</h1>

    <pre style="background: #f5f5f5; padding: 20px; overflow: auto;">
<?php
// 1. Check theme
echo "=== THEME ===\n";
echo "Active theme: " . get_template() . "\n";
echo "Theme directory: " . get_template_directory() . "\n\n";

// 2. Check if files exist
echo "=== FILES ===\n";
$files_to_check = array(
    '/inc/rooms-functions.php',
    '/components/rooms/rooms-list/rooms-list.php',
    '/components/rooms/room-tile/room-tile.php',
    '/components/rooms/image/image.php',
    '/components/rooms/room-category-tag/room-category-tag.php',
    '/test-rooms-list.php'
);

foreach ($files_to_check as $file) {
    $full_path = get_template_directory() . $file;
    echo $file . ": " . (file_exists($full_path) ? "✅ EXISTS" : "❌ NOT FOUND") . "\n";
}

// 3. Check if functions exist
echo "\n=== FUNCTIONS ===\n";
$functions_to_check = array(
    'mroomy_load_room_component',
    'mroomy_rooms_list',
    'mroomy_room_tile',
    'mroomy_room_image',
    'mroomy_room_category_tag',
    'mroomy_room_category_tags',
    'mroomy_parse_room_title',
    'mroomy_get_room_thumbnail_data',
    'mroomy_get_room_categories',
    'mroomy_enqueue_swiper_assets'
);

foreach ($functions_to_check as $func) {
    echo $func . "(): " . (function_exists($func) ? "✅ EXISTS" : "❌ NOT FOUND") . "\n";
}

// 4. Check if rooms-functions.php is included
echo "\n=== INCLUDES ===\n";
$included_files = get_included_files();
$rooms_functions_included = false;
foreach ($included_files as $file) {
    if (strpos($file, 'rooms-functions.php') !== false) {
        echo "rooms-functions.php: ✅ INCLUDED\n";
        echo "Path: " . $file . "\n";
        $rooms_functions_included = true;
        break;
    }
}
if (!$rooms_functions_included) {
    echo "rooms-functions.php: ❌ NOT INCLUDED\n";
}

// 5. Check posts
echo "\n=== POSTS ===\n";
$posts = get_posts(array(
    'post_type' => 'pokoje-dla-dzieci',
    'posts_per_page' => 5,
    'post_status' => 'publish'
));
echo "Published room posts: " . count($posts) . "\n";

// Posts with thumbnails
$posts_with_thumb = get_posts(array(
    'post_type' => 'pokoje-dla-dzieci',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        )
    )
));
echo "Posts with thumbnails: " . count($posts_with_thumb) . "\n";

// 6. Check taxonomy
echo "\n=== TAXONOMY ===\n";
echo "Taxonomy 'przeznaczenie' exists: " . (taxonomy_exists('przeznaczenie') ? "✅ YES" : "❌ NO") . "\n";
if (taxonomy_exists('przeznaczenie')) {
    $terms = get_terms(array(
        'taxonomy' => 'przeznaczenie',
        'hide_empty' => false
    ));
    echo "Terms in 'przeznaczenie': " . count($terms) . "\n";
    foreach ($terms as $term) {
        echo "  - " . $term->name . " (" . $term->slug . ") - " . $term->count . " posts\n";
    }
}

// 7. Try to load component and test
echo "\n=== COMPONENT TEST ===\n";
if (function_exists('mroomy_load_room_component')) {
    echo "Loading room-tile component...\n";
    mroomy_load_room_component('room-tile');
    echo "Component loaded\n";

    if (function_exists('mroomy_room_tile')) {
        echo "mroomy_room_tile() is now available ✅\n";
    }

    echo "\nLoading rooms-list component...\n";
    mroomy_load_room_component('rooms-list');
    echo "Component loaded\n";

    if (function_exists('mroomy_rooms_list')) {
        echo "mroomy_rooms_list() is now available ✅\n";
    }
} else {
    echo "❌ mroomy_load_room_component() not found - components cannot be loaded!\n";
}

// 8. Check if Swiper should be loaded
echo "\n=== SWIPER CHECK ===\n";
echo "is_page_template('test-rooms-list.php'): " . (is_page_template('test-rooms-list.php') ? "YES" : "NO") . "\n";
echo "Current page template: " . get_page_template_slug() . "\n";

// 9. Check hooks
echo "\n=== HOOKS ===\n";
global $wp_filter;
if (isset($wp_filter['wp_enqueue_scripts'])) {
    $found_swiper = false;
    foreach ($wp_filter['wp_enqueue_scripts'] as $priority => $callbacks) {
        foreach ($callbacks as $callback) {
            if (is_array($callback['function']) && is_string($callback['function'][0])) {
                $func = $callback['function'][0];
            } elseif (is_string($callback['function'])) {
                $func = $callback['function'];
            } else {
                continue;
            }

            if (strpos($func, 'swiper') !== false) {
                echo "Found Swiper hook: " . $func . " (priority: " . $priority . ") ✅\n";
                $found_swiper = true;
            }
        }
    }
    if (!$found_swiper) {
        echo "No Swiper enqueue hooks found ❌\n";
    }
}

echo "\n=== END DEBUG ===\n";
?>
    </pre>

    <hr class="my-8">

    <h2 class="text-2xl font-bold mb-4">Test wywołania funkcji</h2>

    <?php
    if (function_exists('mroomy_rooms_list')) {
        echo '<div class="bg-green-100 p-4 mb-4">✅ Funkcja mroomy_rooms_list() istnieje, próbuję wywołać...</div>';

        // Wywołaj funkcję
        mroomy_rooms_list(array(
            'posts_per_page' => 3,
            'title' => 'TEST - Czy to się wyświetli?'
        ));
    } else {
        echo '<div class="bg-red-100 p-4 mb-4">❌ Funkcja mroomy_rooms_list() NIE istnieje!</div>';
    }
    ?>
</div>

<?php
get_footer();