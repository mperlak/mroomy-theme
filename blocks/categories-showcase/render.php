<?php
/**
 * Categories Showcase Block - Server-side rendering
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @param WP_Block $block      Block instance.
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Extract attributes
$title = isset( $attributes['title'] ) ? $attributes['title'] : 'Poszperaj w naszym sklepie';
$show_title = isset( $attributes['showTitle'] ) ? $attributes['showTitle'] : true;
$selected_categories = isset( $attributes['selectedCategories'] ) ? $attributes['selectedCategories'] : array();
$custom_labels = isset( $attributes['customLabels'] ) ? $attributes['customLabels'] : array();
$enable_carousel = isset( $attributes['enableCarousel'] ) ? $attributes['enableCarousel'] : true;
$show_navigation = isset( $attributes['showNavigation'] ) ? $attributes['showNavigation'] : true;

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes( array(
    'class' => 'mroomy-categories-showcase-block'
) );

// If no categories selected, get all top-level categories
if ( empty( $selected_categories ) ) {
    $terms = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 10 // Limit to 10 categories by default
    ) );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        $selected_categories = wp_list_pluck( $terms, 'term_id' );
    }
}

// Check if we have categories to display
if ( empty( $selected_categories ) ) {
    if ( is_admin() ) {
        echo '<div ' . $wrapper_attributes . '>';
        echo '<p class="components-placeholder__label">';
        echo esc_html__( 'Nie znaleziono kategorii produktów do wyświetlenia.', 'mroomy_s' );
        echo '</p>';
        echo '</div>';
    }
    return;
}

// Load categories list component if exists
if ( function_exists( 'mroomy_categories_list' ) ) {
    // Output the block wrapper
    echo '<div ' . $wrapper_attributes . '>';

    // Render categories list
    mroomy_categories_list( array(
        'categories'      => $selected_categories,
        'custom_labels'   => $custom_labels,
        'title'           => $title,
        'show_title'      => $show_title,
        'enable_carousel' => $enable_carousel,
        'show_navigation' => $show_navigation
    ) );

    echo '</div>';
} else {
    // Function not available - show error in admin
    if ( is_admin() ) {
        echo '<div ' . $wrapper_attributes . '>';
        echo '<p class="components-placeholder__label">';
        echo esc_html__( 'Błąd: Funkcja mroomy_categories_list nie została znaleziona.', 'mroomy_s' );
        echo '</p>';
        echo '</div>';
    }
}