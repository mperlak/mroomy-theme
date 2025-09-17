<?php
/**
 * Test page for Rooms List Component
 * Template Name: Test Rooms List
 */

get_header();

// Załaduj komponent
mroomy_load_room_component( 'rooms-list' );

?>

<div class="bg-white min-h-screen">
    <!-- Single test of carousel with large tiles -->
    <?php
    mroomy_rooms_list( array(
        'title' => 'Najlepsze projekty',
        'posts_per_page' => 8,
        'tile_size' => 'large'
    ) );
    ?>
</div>

<?php
get_footer();