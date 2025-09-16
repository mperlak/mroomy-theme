<?php
/**
 * Test autoloader for room components
 * Template Name: Test Autoloader
 */

get_header();
?>

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Test Autoloader dla komponentów pokoi</h1>

    <div class="space-y-4">
        <?php
        // Test 1: Check if rooms-functions.php is loaded
        echo '<div class="p-4 border rounded">';
        echo '<h2 class="text-xl font-semibold mb-2">Test 1: Sprawdzenie funkcji</h2>';

        if ( function_exists( 'mroomy_load_room_component' ) ) {
            echo '<p class="text-green-600">✅ Funkcja mroomy_load_room_component() jest dostępna</p>';
        } else {
            echo '<p class="text-red-600">❌ Funkcja mroomy_load_room_component() nie jest dostępna</p>';
        }

        if ( function_exists( 'mroomy_get_room_thumbnail_data' ) ) {
            echo '<p class="text-green-600">✅ Funkcja mroomy_get_room_thumbnail_data() jest dostępna</p>';
        } else {
            echo '<p class="text-red-600">❌ Funkcja mroomy_get_room_thumbnail_data() nie jest dostępna</p>';
        }

        if ( function_exists( 'mroomy_parse_room_title' ) ) {
            echo '<p class="text-green-600">✅ Funkcja mroomy_parse_room_title() jest dostępna</p>';
        } else {
            echo '<p class="text-red-600">❌ Funkcja mroomy_parse_room_title() nie jest dostępna</p>';
        }
        echo '</div>';

        // Test 2: Check file paths
        echo '<div class="p-4 border rounded">';
        echo '<h2 class="text-xl font-semibold mb-2">Test 2: Sprawdzenie ścieżek plików</h2>';

        $components = array( 'image', 'room-category-tag', 'room-tile', 'rooms-list' );

        foreach ( $components as $component ) {
            $path = get_template_directory() . '/components/rooms/' . $component . '/' . $component . '.php';
            if ( file_exists( $path ) ) {
                echo '<p class="text-green-600">✅ Plik ' . $component . '.php istnieje</p>';
            } else {
                echo '<p class="text-red-600">❌ Plik ' . $component . '.php nie istnieje</p>';
            }

            $css_path = get_template_directory() . '/components/rooms/' . $component . '/' . $component . '.css';
            if ( file_exists( $css_path ) ) {
                echo '<p class="text-green-600">✅ Plik ' . $component . '.css istnieje</p>';
            } else {
                echo '<p class="text-red-600">❌ Plik ' . $component . '.css nie istnieje</p>';
            }
        }
        echo '</div>';

        // Test 3: Test parsing function
        echo '<div class="p-4 border rounded">';
        echo '<h2 class="text-xl font-semibold mb-2">Test 3: Test funkcji parsowania tytułu</h2>';

        $test_titles = array(
            'Projekt biało-żółtego pokoju dla chłopca, Jasia (6 lat) (#1234)',
            'Projekt pokoju dla dziewczynki, Zuzi (8 lat)',
            'Projekt pokoju dla Adama (10 lat) (#567)',
        );

        foreach ( $test_titles as $title ) {
            $parsed = mroomy_parse_room_title( $title );
            echo '<div class="ml-4 mb-2">';
            echo '<p><strong>Oryginalny tytuł:</strong> ' . esc_html( $title ) . '</p>';
            echo '<p><strong>Główna część:</strong> ' . esc_html( $parsed['main'] ) . '</p>';
            echo '<p><strong>Beneficjent:</strong> ' . esc_html( $parsed['beneficiary'] ) . '</p>';
            echo '</div>';
        }
        echo '</div>';

        // Test 4: Check if block is registered
        echo '<div class="p-4 border rounded">';
        echo '<h2 class="text-xl font-semibold mb-2">Test 4: Sprawdzenie bloku Gutenberg</h2>';

        $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
        if ( isset( $block_types['mroomy/rooms-showcase'] ) ) {
            echo '<p class="text-green-600">✅ Blok mroomy/rooms-showcase jest zarejestrowany</p>';
        } else {
            echo '<p class="text-red-600">❌ Blok mroomy/rooms-showcase nie jest zarejestrowany</p>';
        }
        echo '</div>';

        // Test 5: Check image sizes
        echo '<div class="p-4 border rounded">';
        echo '<h2 class="text-xl font-semibold mb-2">Test 5: Sprawdzenie rozmiarów obrazków</h2>';

        global $_wp_additional_image_sizes;
        $sizes_to_check = array( 'room-tile-large', 'room-tile-medium', 'room-tile-small' );

        foreach ( $sizes_to_check as $size ) {
            if ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
                $size_data = $_wp_additional_image_sizes[ $size ];
                echo '<p class="text-green-600">✅ Rozmiar ' . $size . ' jest zarejestrowany (';
                echo $size_data['width'] . 'x' . $size_data['height'] . ')</p>';
            } else {
                echo '<p class="text-red-600">❌ Rozmiar ' . $size . ' nie jest zarejestrowany</p>';
            }
        }
        echo '</div>';
        ?>
    </div>
</div>

<?php
get_footer();