<?php
/**
 * ACF Block: Inspirations Showcase
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

// Inspiration components are loaded in inspirations-functions.php

// Set global variable for preview mode
$GLOBALS['is_preview'] = $is_preview;

// Get ACF field values
$title              = get_field( 'title' ) ?: 'Zainspiruj się';
$button_text        = get_field( 'button_text' ) ?: 'Zobacz wszystkie Inspiracje';
$button_url         = get_field( 'button_url' ) ?: '';
$selection_type     = get_field( 'selection_type' ) ?: 'latest';
$posts_count        = get_field( 'posts_count' ) ?: 10;
$selected_inspirations = get_field( 'selected_inspirations' );
$enable_carousel    = get_field( 'enable_carousel' );
$show_header        = get_field( 'show_header' );
$edge_right         = get_field( 'edge_right' );
$show_navigation    = get_field( 'show_navigation' );

// Build arguments array for mroomy_inspirations_list
$args = array(
    'title'           => $title,
    'button_text'     => $button_text,
    'button_url'      => $button_url,
    'tile_size'       => 'large',
    'enable_carousel' => $enable_carousel !== false, // Default true
    'show_header'     => $show_header !== false,    // Default true
    'show_navigation' => $show_navigation !== false, // Default true
    'class'           => ! empty( $block['className'] ) ? $block['className'] : '',
    'edge_right'      => ! empty( $edge_right ),
);

// Handle different selection types
switch ( $selection_type ) {
    case 'manual':
        // Manual selection - use selected posts
        if ( ! empty( $selected_inspirations ) ) {
            $post_ids = array();
            foreach ( $selected_inspirations as $inspiration ) {
                if ( is_object( $inspiration ) ) {
                    $post_ids[] = $inspiration->ID;
                } elseif ( is_array( $inspiration ) ) {
                    $post_ids[] = $inspiration['ID'];
                } else {
                    $post_ids[] = $inspiration;
                }
            }
            $args['post_ids'] = $post_ids;
        } else {
            // No posts selected - show message in preview
            if ( $is_preview ) {
                echo '<div class="acf-block-placeholder">';
                echo '<p>' . __( 'Wybierz inspiracje do wyświetlenia', 'mroomy' ) . '</p>';
                echo '</div>';
                return;
            }
        }
        break;

    case 'random':
        // Random selection
        $args['posts_per_page'] = $posts_count;
        $args['orderby'] = 'rand';
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

// Check if the inspirations list function exists
if ( function_exists( 'mroomy_inspirations_list' ) ) {
    // For preview mode, add a wrapper to help with styling
    if ( $is_preview ) {
        echo '<div class="acf-inspirations-showcase-preview">';
    }

    // Call the inspirations list component
    mroomy_inspirations_list( $args );

    if ( $is_preview ) {
        echo '</div>';
    }
} else {
    // Function not found - show error in preview
    if ( $is_preview ) {
        echo '<div class="acf-block-error">';
        echo '<p>' . __( 'Błąd: Funkcja mroomy_inspirations_list nie została znaleziona', 'mroomy' ) . '</p>';
        echo '</div>';
    }
}