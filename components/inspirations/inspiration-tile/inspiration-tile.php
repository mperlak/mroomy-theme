<?php
/**
 * Inspiration Tile Component
 *
 * Displays an inspiration tile with image and title
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display inspiration tile
 *
 * @param array $args {
 *     Optional. Arguments for displaying the inspiration tile.
 *
 *     @type int    $post_id       Post ID. Default is current post
 *     @type string $size          Tile size: 'large', 'mobile'. Default 'large'
 *     @type string $class         Additional CSS classes
 * }
 */
function mroomy_inspiration_tile( $args = array() ) {
    $defaults = array(
        'post_id' => 0,
        'size'    => 'large',
        'class'   => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Get post ID
    $post_id = $args['post_id'] ? $args['post_id'] : get_the_ID();

    if ( ! $post_id ) {
        return;
    }

    // Get post data
    $post = get_post( $post_id );
    if ( ! $post || $post->post_type !== 'inspiracja' ) {
        return;
    }

    // Get thumbnail data
    $thumbnail_data = mroomy_get_inspiration_thumbnail_data( $post_id );

    // Get permalink
    $permalink = get_permalink( $post_id );

    // Build CSS classes with Tailwind utilities
    $css_classes = array(
        'inspiration-tile',
        'inspiration-tile-' . $args['size'],
        'bg-white',
        'rounded-[16px]',
        'overflow-hidden',
        'flex',
        'flex-col',
        'border',
        'border-[#e0e0e0]',
        'shadow-[0px_4px_6px_rgba(0,0,0,0.08)]',
        'transition-transform',
        'duration-200',
        'hover:scale-[1.02]',
        'group'
    );

    // Add size-specific fixed width and min-height to accommodate longer titles
    if ( $args['size'] === 'mobile' ) {
        $css_classes[] = 'w-[282px]';
        $css_classes[] = 'min-h-[334px]';
    } else {
        $css_classes[] = 'w-[384px]';
        $css_classes[] = 'min-h-[384px]';
    }

    if ( ! empty( $args['class'] ) ) {
        $css_classes[] = $args['class'];
    }

    // Determine image size based on tile size
    $image_size = ( $args['size'] === 'mobile' ) ? 'inspiration-tile-mobile' : 'inspiration-tile-large';

    // Load required components
    mroomy_load_room_component( 'image' );

    ?>
    <article class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <a href="<?php echo esc_url( $permalink ); ?>" class="flex flex-col h-full no-underline">
            <?php if ( $thumbnail_data ) : ?>
                <div class="relative overflow-hidden flex-shrink-0">
                    <?php if ( $args['size'] === 'mobile' ) : ?>
                        <!-- Mobile: Fixed height image -->
                        <div class="relative w-full">
                            <?php if ( $thumbnail_data['id'] ) : ?>
                                <?php echo wp_get_attachment_image(
                                    $thumbnail_data['id'],
                                    $image_size,
                                    false,
                                    array(
                                        'class' => 'w-full h-auto object-cover',
                                        'alt'   => esc_attr( $thumbnail_data['alt'] ? $thumbnail_data['alt'] : $post->post_title )
                                    )
                                ); ?>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <!-- Desktop: Fixed dimensions -->
                        <div class="relative w-full">
                            <?php if ( $thumbnail_data['id'] ) : ?>
                                <?php echo wp_get_attachment_image(
                                    $thumbnail_data['id'],
                                    $image_size,
                                    false,
                                    array(
                                        'class' => 'w-full h-auto object-cover',
                                        'alt'   => esc_attr( $thumbnail_data['alt'] ? $thumbnail_data['alt'] : $post->post_title )
                                    )
                                ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="flex flex-col justify-center items-start flex-grow px-4 py-6 min-h-[88px]">
                <h3 class="font-nunito font-extrabold text-[20px] leading-[26px] text-[#222222] m-0 line-clamp-2">
                    <?php echo esc_html( $post->post_title ); ?>
                </h3>
            </div>
        </a>
    </article>
    <?php
}