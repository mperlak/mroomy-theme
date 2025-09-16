<?php
/**
 * Rooms Components Functions
 *
 * Helper functions and autoloader for room components
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Autoloader for room components
 *
 * @param string $component_name Name of the component to load
 */
function mroomy_load_room_component( $component_name ) {
    $component_path = get_template_directory() . '/components/rooms/' . $component_name . '/' . $component_name . '.php';

    if ( file_exists( $component_path ) ) {
        include_once $component_path;
    }
}

/**
 * Enqueue styles for room components
 */
function mroomy_enqueue_room_styles() {
    // Check if we should load room styles
    if ( is_singular( 'pokoje-dla-dzieci' ) || has_block( 'mroomy/rooms-showcase' ) || is_post_type_archive( 'pokoje-dla-dzieci' ) ) {

        // Check if combined CSS file exists
        $combined_css = get_template_directory() . '/components/rooms/rooms-all.css';
        if ( file_exists( $combined_css ) ) {
            wp_enqueue_style(
                'mroomy-rooms-components',
                get_template_directory_uri() . '/components/rooms/rooms-all.css',
                array(),
                filemtime( $combined_css )
            );
        } else {
            // Load individual CSS files if combined doesn't exist
            $components = array( 'image', 'room-category-tag', 'room-tile', 'rooms-list' );
            foreach ( $components as $component ) {
                $css_file = get_template_directory() . '/components/rooms/' . $component . '/' . $component . '.css';
                if ( file_exists( $css_file ) ) {
                    wp_enqueue_style(
                        'mroomy-room-' . $component,
                        get_template_directory_uri() . '/components/rooms/' . $component . '/' . $component . '.css',
                        array(),
                        filemtime( $css_file )
                    );
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'mroomy_enqueue_room_styles' );

/**
 * Get room thumbnail data
 *
 * @param int $post_id Post ID
 * @return array|false Array with image data or false if no thumbnail
 */
function mroomy_get_room_thumbnail_data( $post_id ) {
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

/**
 * Parse room title to extract main part and beneficiary
 *
 * @param string $title Full post title
 * @return array Array with 'main' and 'beneficiary' keys
 */
function mroomy_parse_room_title( $title ) {
    $result = array(
        'main'        => $title,
        'beneficiary' => ''
    );

    // Remove project number from the end if exists
    $title = preg_replace( '/\s*\(#\d+\)\s*$/', '', $title );

    // Pattern for "Projekt [description] dla [type], [name] ([age])"
    if ( preg_match( '/^(Projekt\s+.+?)\s+dla\s+(.+?)$/', $title, $matches ) ) {
        $result['main'] = trim( $matches[1] );
        // Usuń "dla" z beneficjenta jeśli zostało
        $beneficiary = trim( $matches[2] );
        $result['beneficiary'] = $beneficiary;
    }
    // Pattern for "Projekt [description], [name] ([age])"
    elseif ( preg_match( '/^(Projekt\s+.+?),\s+(.+?)$/', $title, $matches ) ) {
        $result['main']        = trim( $matches[1] );
        $result['beneficiary'] = trim( $matches[2] );
    }

    return $result;
}

/**
 * Get room categories
 *
 * @param int $post_id Post ID
 * @return array Array of category slugs
 */
function mroomy_get_room_categories( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $terms = wp_get_post_terms( $post_id, 'przeznaczenie', array( 'fields' => 'slugs' ) );

    if ( is_wp_error( $terms ) ) {
        return array();
    }

    return $terms;
}

/**
 * Check if room is for specific category
 *
 * @param string $category Category to check ('boy', 'girl', 'siblings')
 * @param int    $post_id  Post ID
 * @return bool
 */
function mroomy_room_is_for( $category, $post_id = null ) {
    $categories = mroomy_get_room_categories( $post_id );

    $category_mapping = array(
        'boy'      => 'pokoje-dla-chlopcow',
        'girl'     => 'pokoje-dla-dziewczynek',
        'siblings' => 'pokoje-dla-rodzenstwa'
    );

    $slug = isset( $category_mapping[ $category ] ) ? $category_mapping[ $category ] : $category;

    return in_array( $slug, $categories );
}

/**
 * Register custom image sizes for room tiles
 */
function mroomy_register_room_image_sizes() {
    add_image_size( 'room-tile-large', 386, 290, true );
    add_image_size( 'room-tile-medium', 216, 162, true );
    add_image_size( 'room-tile-small', 163, 122, true );
}
add_action( 'after_setup_theme', 'mroomy_register_room_image_sizes' );

/**
 * Register Rooms Showcase block
 */
function mroomy_register_rooms_showcase_block() {
    $block_path = get_template_directory() . '/blocks/rooms-showcase';

    if ( file_exists( $block_path . '/block.json' ) ) {
        register_block_type( $block_path );
    }
}
add_action( 'init', 'mroomy_register_rooms_showcase_block' );