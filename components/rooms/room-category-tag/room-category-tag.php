<?php
/**
 * Room Category Tag Component
 *
 * Displays category tags for room posts
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display single room category tag
 *
 * @param array $args {
 *     Optional. Arguments for displaying the tag.
 *
 *     @type string $category Category slug ('boy', 'girl', 'siblings' or direct taxonomy term slug)
 *     @type string $label    Custom label text. If not provided, default label will be used
 *     @type string $class    Additional CSS classes
 * }
 */
function mroomy_room_category_tag( $args = array() ) {
    $defaults = array(
        'category' => '',
        'label'    => '',
        'class'    => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // If no category specified, return
    if ( empty( $args['category'] ) ) {
        return;
    }

    // Map short category names to taxonomy terms
    $category_mapping = array(
        'boy'      => 'pokoje-dla-chlopcow',
        'girl'     => 'pokoje-dla-dziewczynek',
        'siblings' => 'pokoje-dla-rodzenstwa'
    );

    // Get the actual slug
    $slug = isset( $category_mapping[ $args['category'] ] ) ? $category_mapping[ $args['category'] ] : $args['category'];

    // Get default labels
    $default_labels = array(
        'pokoje-dla-chlopcow'     => 'Dla chłopca',
        'pokoje-dla-dziewczynek'  => 'Dla dziewczynki',
        'pokoje-dla-rodzenstwa'   => 'Dla rodzeństwa'
    );

    // Determine label to use
    $label = ! empty( $args['label'] ) ? $args['label'] : ( isset( $default_labels[ $slug ] ) ? $default_labels[ $slug ] : ucfirst( $args['category'] ) );

    // Determine CSS class for styling
    $type_class = '';
    if ( in_array( $slug, array( 'pokoje-dla-chlopcow' ) ) ) {
        $type_class = 'mroomy-room-category-tag--boy';
    } elseif ( in_array( $slug, array( 'pokoje-dla-dziewczynek' ) ) ) {
        $type_class = 'mroomy-room-category-tag--girl';
    } elseif ( in_array( $slug, array( 'pokoje-dla-rodzenstwa' ) ) ) {
        $type_class = 'mroomy-room-category-tag--siblings';
    }

    // Build CSS classes
    $css_classes = array(
        'mroomy-room-category-tag',
        $type_class
    );

    if ( ! empty( $args['class'] ) ) {
        $css_classes[] = $args['class'];
    }

    // Output the tag
    ?>
    <span class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <?php echo esc_html( $label ); ?>
    </span>
    <?php
}

/**
 * Display room category tags for a post
 *
 * @param array $args {
 *     Optional. Arguments for displaying the tags.
 *
 *     @type int    $post_id Post ID. Default is current post
 *     @type string $class   Additional CSS classes for container
 * }
 */
function mroomy_room_category_tags( $args = array() ) {
    $defaults = array(
        'post_id' => 0,
        'class'   => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Get post ID
    $post_id = $args['post_id'] ? $args['post_id'] : get_the_ID();

    if ( ! $post_id ) {
        return;
    }

    // Get terms for this post
    $terms = wp_get_post_terms( $post_id, 'przeznaczenie', array( 'fields' => 'all' ) );

    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return;
    }

    // Build container CSS classes
    $css_classes = array(
        'mroomy-room-category-tags'
    );

    if ( ! empty( $args['class'] ) ) {
        $css_classes[] = $args['class'];
    }

    // Output container with tags
    ?>
    <div class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <?php
        foreach ( $terms as $term ) {
            mroomy_room_category_tag( array(
                'category' => $term->slug,
                'label'    => $term->name
            ) );
        }
        ?>
    </div>
    <?php
}