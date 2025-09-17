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

    // Build CSS classes with Tailwind utilities based on category
    $base_classes = array(
        'inline-flex',
        'items-center',
        'px-3',
        'py-1.5',
        'rounded-md',  // More rounded like in Figma
        'font-nunito',
        'font-semibold',
        'text-[14px]',
        'leading-[18px]',
        'backdrop-blur-sm'
    );

    // Add category-specific colors (from Figma: #9ecbeb for boy)
    if ( in_array( $slug, array( 'pokoje-dla-chlopcow' ) ) ) {
        $base_classes[] = 'bg-[#9ecbeb]/90';
        $base_classes[] = 'text-black/75';  // rgba(0,0,0,0.75) from Figma
    } elseif ( in_array( $slug, array( 'pokoje-dla-dziewczynek' ) ) ) {
        $base_classes[] = 'bg-pink-200/90';
        $base_classes[] = 'text-black/75';
    } elseif ( in_array( $slug, array( 'pokoje-dla-rodzenstwa' ) ) ) {
        $base_classes[] = 'bg-purple-200/90';
        $base_classes[] = 'text-black/75';
    } else {
        $base_classes[] = 'bg-gray-200/90';
        $base_classes[] = 'text-black/75';
    }

    $css_classes = $base_classes;

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

    // Build container CSS classes with Tailwind
    $css_classes = array(
        'flex',
        'gap-2',
        'flex-wrap'
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