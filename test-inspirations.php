<?php
/**
 * Template Name: Test Inspirations
 */

get_header();
?>

<div class="container mx-auto py-8">
    <h1 class="text-3xl mb-8">Test komponentu Inspiracje</h1>

    <?php
    // Test funkcji bezpośrednio
    if ( function_exists( 'mroomy_inspirations_list' ) ) {
        mroomy_inspirations_list( array(
            'title' => 'Zainspiruj się',
            'posts_per_page' => 8,
            'enable_carousel' => true,
            'show_header' => true,
            'edge_right' => false
        ) );
    } else {
        echo '<p>Funkcja mroomy_inspirations_list nie została znaleziona!</p>';
    }
    ?>
</div>

<?php
get_footer();