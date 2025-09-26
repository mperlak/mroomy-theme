<?php
/**
 * Categories Components Functions
 *
 * Helper functions and autoloader for product category components
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Autoloader for category components
 *
 * @param string $component_name Name of the component to load
 */
function mroomy_load_category_component( $component_name ) {
    $component_path = get_template_directory() . '/components/categories/' . $component_name . '/' . $component_name . '.php';

    if ( file_exists( $component_path ) ) {
        include_once $component_path;
    }
}

/**
 * Get category thumbnail data
 *
 * @param int $term_id Term ID
 * @return array|false Array with image data or false if no thumbnail
 */
function mroomy_get_category_thumbnail( $term_id ) {
    // Get WooCommerce category thumbnail
    $thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

    if ( ! $thumbnail_id ) {
        return false;
    }

    return array(
        'id'  => $thumbnail_id,
        'url' => wp_get_attachment_image_url( $thumbnail_id, 'full' ),
        'alt' => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true )
    );
}

/**
 * Get category thumbnail URL with size
 *
 * @param int $term_id Term ID
 * @param string $size Image size
 * @return string|false Image URL or false if no thumbnail
 */
function mroomy_get_category_thumbnail_url( $term_id, $size = 'category-circle' ) {
    $thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );

    if ( ! $thumbnail_id ) {
        // Return placeholder image
        return get_template_directory_uri() . '/assets/images/category-placeholder.png';
    }

    return wp_get_attachment_image_url( $thumbnail_id, $size );
}

/**
 * Register custom image sizes for category circles
 */
function mroomy_register_category_image_sizes() {
    // Desktop size
    add_image_size( 'category-circle', 190, 190, true );

    // Tablet size
    add_image_size( 'category-circle-tablet', 163, 163, true );

    // Mobile size
    add_image_size( 'category-circle-mobile', 136, 136, true );
}
add_action( 'after_setup_theme', 'mroomy_register_category_image_sizes' );

/**
 * Load category components on init
 */
function mroomy_init_category_components() {
    // Load all components
    mroomy_load_category_component( 'category-circle' );
    mroomy_load_category_component( 'categories-list' );
}
add_action( 'init', 'mroomy_init_category_components' );

/**
 * Check if we should enqueue Swiper for categories
 */
function mroomy_categories_need_swiper() {
    // Check if we're on a page that needs Swiper for categories
    if ( has_block( 'mroomy/categories-showcase' ) ) {
        return true;
    }

    // Check for shortcode or function call in content
    global $post;
    if ( $post && ( strpos( $post->post_content, 'mroomy_categories_list' ) !== false ||
                    strpos( $post->post_content, '[categories_showcase' ) !== false ) ) {
        return true;
    }

    // Check if on product category archive
    if ( is_product_category() ) {
        return true;
    }

    return false;
}