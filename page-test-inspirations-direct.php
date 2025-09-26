<?php
/**
 * Template Name: Test Inspiracje Direct
 */

get_header();
?>

<div class="py-8">
    <?php
    // Test funkcji bezpośrednio - nie przez Gutenberg
    if ( function_exists( 'mroomy_inspirations_list' ) ) {
        echo '<div class="test-wrapper">';
        mroomy_inspirations_list( array(
            'title' => 'Zainspiruj się',
            'button_text' => 'Zobacz wszystkie Inspiracje',
            'posts_per_page' => 6,
            'enable_carousel' => true,
            'show_header' => true,
            'edge_right' => false
        ) );
        echo '</div>';
    } else {
        echo '<div class="container mx-auto px-4">';
        echo '<p class="text-red-500 text-xl">Błąd: Funkcja mroomy_inspirations_list nie została znaleziona!</p>';
        echo '</div>';
    }
    ?>
</div>

<?php
get_footer();