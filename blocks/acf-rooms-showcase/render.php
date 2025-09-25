<?php
/**
 * ACF Block: Rooms Showcase
 * Template for rendering the block
 *
 * @package Mroomy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 * @var array $context The context provided to the block by the post or its parent block.
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Room components are loaded in functions.php

// Set global variable for preview mode
$GLOBALS['is_preview'] = $is_preview;

// Get ACF field values
$title              = get_field( 'title' ) ?: 'Najlepsze projekty';
$button_text        = get_field( 'button_text' ) ?: 'Zobacz wszystkie Projekty';
$button_url         = get_field( 'button_url' ) ?: '/pokoje-dla-dzieci/';
$selection_type     = get_field( 'selection_type' ) ?: 'latest';
$posts_count        = get_field( 'posts_count' ) ?: 10;
$selected_rooms     = get_field( 'selected_rooms' );
$selected_category  = get_field( 'selected_category' );
$tile_size          = get_field( 'tile_size' ) ?: 'large';
$enable_carousel    = get_field( 'enable_carousel' );
$show_header        = get_field( 'show_header' );
$show_tile_buttons  = get_field( 'show_tile_buttons' );
$edge_right         = get_field( 'edge_right' );

// Build arguments array for mroomy_rooms_list
$args = array(
    'title'           => $title,
    'button_text'     => $button_text,
    'button_url'      => $button_url,
    'tile_size'       => $tile_size,
    'enable_carousel' => $enable_carousel !== false, // Default true
    'show_header'     => $show_header !== false,    // Default true
    'class'           => ! empty( $block['className'] ) ? $block['className'] : '',
    'edge_right'      => ! empty( $edge_right ),
);

// Pass show_actions parameter for tile buttons
// This will be passed down to mroomy_room_tile through rooms_list
if ( $show_tile_buttons === false ) {
    $args['show_actions'] = false;
}

// Handle different selection types
switch ( $selection_type ) {
    case 'manual':
        // Manual selection - use selected posts
        if ( ! empty( $selected_rooms ) ) {
            $post_ids = array();
            foreach ( $selected_rooms as $room ) {
                if ( is_object( $room ) ) {
                    $post_ids[] = $room->ID;
                } elseif ( is_array( $room ) ) {
                    $post_ids[] = $room['ID'];
                } else {
                    $post_ids[] = $room;
                }
            }
            $args['post_ids'] = $post_ids;
        } else {
            // No posts selected - show message in preview
            if ( $is_preview ) {
                echo '<div class="acf-block-placeholder">';
                echo '<p>' . __( 'Wybierz projekty do wyświetlenia', 'mroomy' ) . '</p>';
                echo '</div>';
                return;
            }
        }
        break;

    case 'category':
        // Category selection
        if ( ! empty( $selected_category ) ) {
            // Convert term ID to slug for the query
            $term = get_term( $selected_category, 'przeznaczenie' );
            if ( $term && ! is_wp_error( $term ) ) {
                $args['categories'] = array( $term->slug );
            }
        } else {
            // No category selected - show message in preview
            if ( $is_preview ) {
                echo '<div class="acf-block-placeholder">';
                echo '<p>' . __( 'Wybierz kategorię do wyświetlenia', 'mroomy' ) . '</p>';
                echo '</div>';
                return;
            }
        }
        break;

    default:
        // Latest posts (default)
        $args['posts_per_page'] = $posts_count;
        break;
}

// Add block anchor if provided
if ( ! empty( $block['anchor'] ) ) {
    $args['id'] = $block['anchor'];
}

// Add block alignment class if provided
if ( ! empty( $block['align'] ) ) {
    $args['class'] .= ' align' . $block['align'];
}

// Check if the rooms list function exists
if ( function_exists( 'mroomy_rooms_list' ) ) {
    // For preview mode, add a wrapper to help with styling
    if ( $is_preview ) {
        echo '<div class="acf-rooms-showcase-preview">';
    }

    // Call the existing rooms list component
    mroomy_rooms_list( $args );

    if ( $is_preview ) {
        echo '</div>';
    }
} else {
    // Function not found - show error in preview
    if ( $is_preview ) {
        echo '<div class="acf-block-error">';
        echo '<p>' . __( 'Błąd: Funkcja mroomy_rooms_list nie została znaleziona', 'mroomy' ) . '</p>';
        echo '</div>';
    }
}