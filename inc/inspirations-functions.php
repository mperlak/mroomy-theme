<?php
/**
 * Inspirations Components Functions
 *
 * Helper functions and autoloader for inspiration components
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Autoloader for inspiration components
 *
 * @param string $component_name Name of the component to load
 */
function mroomy_load_inspiration_component( $component_name ) {
    $component_path = get_template_directory() . '/components/inspirations/' . $component_name . '/' . $component_name . '.php';

    if ( file_exists( $component_path ) ) {
        include_once $component_path;
    }
}

/**
 * Get inspiration thumbnail data
 *
 * @param int $post_id Post ID
 * @return array|false Array with image data or false if no thumbnail
 */
function mroomy_get_inspiration_thumbnail_data( $post_id ) {
    // Get image from ACF field 'header_picture' instead of featured image
    $header_picture = get_field( 'header_picture', $post_id );

    if ( ! $header_picture ) {
        // Fallback to featured image if ACF field is empty
        $thumbnail_id = get_post_thumbnail_id( $post_id );

        if ( ! $thumbnail_id ) {
            return false;
        }

        return array(
            'id'  => $thumbnail_id,
            'url' => wp_get_attachment_image_url( $thumbnail_id, 'full' ),
            'alt' => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true )
        );
    }

    // Handle ACF image field (can return ID or array)
    if ( is_numeric( $header_picture ) ) {
        // If it's an ID
        $image_id = $header_picture;
        return array(
            'id'  => $image_id,
            'url' => wp_get_attachment_image_url( $image_id, 'full' ),
            'alt' => get_post_meta( $image_id, '_wp_attachment_image_alt', true )
        );
    } elseif ( is_array( $header_picture ) ) {
        // If it's an array with image data
        return array(
            'id'  => isset( $header_picture['ID'] ) ? $header_picture['ID'] : $header_picture['id'],
            'url' => isset( $header_picture['url'] ) ? $header_picture['url'] : '',
            'alt' => isset( $header_picture['alt'] ) ? $header_picture['alt'] : ''
        );
    }

    return false;
}

/**
 * Register custom image sizes for inspiration tiles
 */
function mroomy_register_inspiration_image_sizes() {
    // Desktop tile image size
    add_image_size( 'inspiration-tile-large', 384, 296, true );

    // Mobile tile image size
    add_image_size( 'inspiration-tile-mobile', 282, 225, true );
}
add_action( 'after_setup_theme', 'mroomy_register_inspiration_image_sizes' );

/**
 * Load inspiration components on init
 */
function mroomy_init_inspiration_components() {
    // Load all components
    mroomy_load_inspiration_component( 'inspiration-tile' );
    mroomy_load_inspiration_component( 'inspirations-list' );
}
add_action( 'init', 'mroomy_init_inspiration_components' );

/**
 * Check if we should enqueue Swiper for inspirations
 */
function mroomy_inspirations_need_swiper() {
    // Check if we're on a page that needs Swiper for inspirations
    if ( has_block( 'acf/acf-inspirations-showcase' ) ) {
        return true;
    }

    // Check for shortcode or function call in content
    global $post;
    if ( $post && ( strpos( $post->post_content, 'mroomy_inspirations_list' ) !== false ||
                    strpos( $post->post_content, '[inspirations_list' ) !== false ) ) {
        return true;
    }

    // Check if on inspirations archive
    if ( is_post_type_archive( 'inspiracja' ) ) {
        return true;
    }

    return false;
}

/**
 * Enqueue assets for inspiration components
 * Note: Swiper is already enqueued by rooms-functions.php
 * This is just to ensure it's loaded when needed for inspirations
 */
function mroomy_enqueue_inspiration_assets() {
    // Swiper is already handled by mroomy_enqueue_swiper_assets() in rooms-functions.php
    // We just need to make sure it detects our block

    // Optional: Add any inspiration-specific styles here if needed
    if ( mroomy_inspirations_need_swiper() ) {
        // Inspiration-specific styles could go here
        // For now, we're using Tailwind classes only
    }
}
add_action( 'wp_enqueue_scripts', 'mroomy_enqueue_inspiration_assets' );