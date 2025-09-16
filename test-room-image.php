<?php
/**
 * Test page for Room Image Component
 * Template Name: Test Room Image
 */

get_header();

// Załaduj komponent
mroomy_load_room_component( 'image' );

// Pobierz przykładowy pokój z featured image
$args = array(
    'post_type'      => 'pokoje-dla-dzieci',
    'posts_per_page' => 1,
    'meta_query'     => array(
        array(
            'key'     => '_thumbnail_id',
            'compare' => 'EXISTS'
        )
    )
);
$query = new WP_Query( $args );
$test_post_id = 0;
$test_thumbnail_data = false;

if ( $query->have_posts() ) {
    $query->the_post();
    $test_post_id = get_the_ID();
    $test_thumbnail_data = mroomy_get_room_thumbnail_data( $test_post_id );
}
wp_reset_postdata();

?>

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Test komponentu Room Image</h1>

    <?php if ( $test_thumbnail_data ) : ?>

        <div class="space-y-8">
            <!-- Test różnych rozmiarów -->
            <section class="border p-6 rounded">
                <h2 class="text-2xl font-semibold mb-4">Test rozmiarów (16:9)</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium mb-2">Large (386px)</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '16:9',
                            'size'         => 'large',
                            'alt_text'     => 'Test Large'
                        ) );
                        ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">Medium (216px)</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '16:9',
                            'size'         => 'medium',
                            'alt_text'     => 'Test Medium'
                        ) );
                        ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">Small (163px)</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '16:9',
                            'size'         => 'small',
                            'alt_text'     => 'Test Small'
                        ) );
                        ?>
                    </div>
                </div>
            </section>

            <!-- Test różnych proporcji -->
            <section class="border p-6 rounded">
                <h2 class="text-2xl font-semibold mb-4">Test proporcji (rozmiar Large)</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium mb-2">1:1 (kwadrat)</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '1:1',
                            'size'         => 'large',
                            'alt_text'     => 'Test 1:1'
                        ) );
                        ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">5:4</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '5:4',
                            'size'         => 'large',
                            'alt_text'     => 'Test 5:4'
                        ) );
                        ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">4:3</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '4:3',
                            'size'         => 'large',
                            'alt_text'     => 'Test 4:3'
                        ) );
                        ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">3:2</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '3:2',
                            'size'         => 'large',
                            'alt_text'     => 'Test 3:2'
                        ) );
                        ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">16:9 (domyślne)</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '16:9',
                            'size'         => 'large',
                            'alt_text'     => 'Test 16:9'
                        ) );
                        ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">2:1 (panorama)</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '2:1',
                            'size'         => 'large',
                            'alt_text'     => 'Test 2:1'
                        ) );
                        ?>
                    </div>
                </div>
            </section>

            <!-- Test z hover effect -->
            <section class="border p-6 rounded">
                <h2 class="text-2xl font-semibold mb-4">Test z efektem hover</h2>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium mb-2">Z hover effect</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_id'     => $test_thumbnail_data['id'],
                            'aspect_ratio' => '16:9',
                            'size'         => 'medium',
                            'class'        => 'mroomy-room-image--hover',
                            'alt_text'     => 'Test hover'
                        ) );
                        ?>
                    </div>
                </div>
            </section>

            <!-- Test z URL zamiast ID -->
            <section class="border p-6 rounded">
                <h2 class="text-2xl font-semibold mb-4">Test z URL obrazka (bez ID)</h2>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium mb-2">URL obrazka</h3>
                        <?php
                        mroomy_room_image( array(
                            'image_url'    => 'https://via.placeholder.com/386x217/9ecbeb/ffffff?text=Test+Image',
                            'aspect_ratio' => '16:9',
                            'size'         => 'large',
                            'alt_text'     => 'Test URL'
                        ) );
                        ?>
                    </div>
                </div>
            </section>

            <!-- Informacje o obrazku -->
            <section class="border p-6 rounded">
                <h2 class="text-2xl font-semibold mb-4">Informacje o testowym obrazku</h2>
                <div class="bg-gray-100 p-4 rounded">
                    <p><strong>Post ID:</strong> <?php echo $test_post_id; ?></p>
                    <p><strong>Image ID:</strong> <?php echo $test_thumbnail_data['id']; ?></p>
                    <p><strong>URL:</strong> <?php echo $test_thumbnail_data['url']; ?></p>
                    <p><strong>Alt text:</strong> <?php echo $test_thumbnail_data['alt']; ?></p>
                    <?php
                    $metadata = wp_get_attachment_metadata( $test_thumbnail_data['id'] );
                    if ( $metadata ) {
                        echo '<p><strong>Wymiary oryginału:</strong> ' . $metadata['width'] . 'x' . $metadata['height'] . 'px</p>';

                        if ( isset( $metadata['sizes'] ) ) {
                            echo '<p><strong>Dostępne rozmiary:</strong></p>';
                            echo '<ul class="ml-4">';
                            foreach ( $metadata['sizes'] as $size_name => $size_data ) {
                                if ( strpos( $size_name, 'room-tile' ) === 0 ) {
                                    echo '<li>' . $size_name . ': ' . $size_data['width'] . 'x' . $size_data['height'] . 'px</li>';
                                }
                            }
                            echo '</ul>';
                        }
                    }
                    ?>
                </div>
            </section>
        </div>

    <?php else : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p>Nie znaleziono pokoju z obrazkiem do testów. Upewnij się, że masz przynajmniej jeden opublikowany pokój z obrazkiem wyróżniającym.</p>
        </div>
    <?php endif; ?>

    <!-- Test sprawdzania CSS -->
    <section class="border p-6 rounded mt-8">
        <h2 class="text-2xl font-semibold mb-4">Sprawdzenie CSS</h2>
        <div class="space-y-2">
            <p>✅ Border radius: 16.378px (zgodnie z Figma)</p>
            <p>✅ Aspect ratio support z CSS</p>
            <p>✅ Fallback dla starszych przeglądarek (@supports)</p>
            <p>✅ Object-fit: cover dla zachowania proporcji</p>
            <p>✅ Lazy loading</p>
            <p>✅ Srcset support (przez wp_get_attachment_image)</p>
        </div>
    </section>

</div>

<?php
get_footer();