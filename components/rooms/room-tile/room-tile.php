<?php
/**
 * Room Tile Component
 *
 * Displays a room tile with image, title, and category tags
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display room tile
 *
 * @param array $args {
 *     Optional. Arguments for displaying the room tile.
 *
 *     @type int    $post_id       Post ID. Default is current post
 *     @type string $size          Tile size: 'large', 'medium', 'small'. Default 'large'
 *     @type bool   $show_title    Show title. Default true
 *     @type bool   $show_excerpt  Show excerpt/description. Default true
 *     @type bool   $show_beneficiary Show beneficiary text. Default true
 *     @type bool   $show_actions  Show action button. Default true
 *     @type bool   $show_tags     Show category tags. Default true
 *     @type string $button_text   Custom button text. Default 'Zobacz projekt'
 *     @type string $button_url    Custom button URL. Default is post permalink
 *     @type string $class         Additional CSS classes
 * }
 */
function mroomy_room_tile( $args = array() ) {
    $defaults = array(
        'post_id'          => 0,
        'size'             => 'large',
        'show_title'       => true,
        'show_excerpt'     => true,
        'show_beneficiary' => true,
        'show_actions'     => true,
        'show_tags'        => true,
        'button_text'      => 'Zobacz projekt',
        'button_url'       => '',
        'class'            => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Get post ID
    $post_id = $args['post_id'] ? $args['post_id'] : get_the_ID();

    if ( ! $post_id ) {
        return;
    }

    // Get post data
    $post = get_post( $post_id );
    if ( ! $post || $post->post_type !== 'pokoje-dla-dzieci' ) {
        return;
    }

    // Parse title for main part and beneficiary
    $title_data = mroomy_parse_room_title( $post->post_title );

    // Get thumbnail data
    $thumbnail_data = mroomy_get_room_thumbnail_data( $post_id );

    // Get excerpt
    $excerpt = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( $post->post_content, 20 );

    // Get button URL
    $button_url = ! empty( $args['button_url'] ) ? $args['button_url'] : get_permalink( $post_id );

    // Build CSS classes with Tailwind utilities
    $css_classes = array(
        'bg-white',
        'rounded-2xl',
        'overflow-hidden',
        'shadow-card',
        'hover:shadow-lg',
        'transition-shadow',
        'duration-200',
        'flex',
        'flex-col',
        'h-full'
    );

    if ( ! empty( $args['class'] ) ) {
        $css_classes[] = $args['class'];
    }

    // Determine image size based on tile size
    $image_sizes = array(
        'large'  => 'large',
        'medium' => 'medium',
        'small'  => 'small'
    );
    $image_size = isset( $image_sizes[ $args['size'] ] ) ? $image_sizes[ $args['size'] ] : 'large';

    // Determine aspect ratio based on tile size
    $aspect_ratios = array(
        'large'  => '4:3',
        'medium' => '4:3',
        'small'  => '4:3'
    );
    $aspect_ratio = isset( $aspect_ratios[ $args['size'] ] ) ? $aspect_ratios[ $args['size'] ] : '4:3';

    // Load required components
    mroomy_load_room_component( 'image' );
    mroomy_load_room_component( 'room-category-tag' );

    ?>
    <article class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <?php if ( $thumbnail_data ) : ?>
            <div class="relative">
                <?php
                mroomy_room_image( array(
                    'image_id'     => $thumbnail_data['id'],
                    'aspect_ratio' => $aspect_ratio,
                    'size'         => $image_size,
                    'alt_text'     => $thumbnail_data['alt'] ? $thumbnail_data['alt'] : $title_data['main']
                ) );
                ?>

                <?php if ( $args['show_tags'] ) : ?>
                    <div class="absolute bottom-4 left-4 flex gap-2 flex-wrap">
                        <?php
                        mroomy_room_category_tags( array(
                            'post_id' => $post_id
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="p-6 flex flex-col flex-grow">
            <?php if ( $args['show_title'] ) : ?>
                <h3 class="font-nunito font-bold text-headline-small text-neutral-text mb-2">
                    <a href="<?php echo esc_url( $button_url ); ?>" class="hover:text-primary transition-colors duration-200">
                        <?php echo esc_html( $title_data['main'] ); ?>
                    </a>
                </h3>
            <?php endif; ?>

            <?php if ( $args['show_excerpt'] && $excerpt ) : ?>
                <div class="text-body-2 text-neutral-text-secondary mb-3 line-clamp-2">
                    <?php echo esc_html( $excerpt ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_beneficiary'] && ! empty( $title_data['beneficiary'] ) ) : ?>
                <div class="text-body-2 text-primary font-semibold mb-4">
                    <?php echo esc_html( $title_data['beneficiary'] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_actions'] ) : ?>
                <div class="mt-auto">
                    <a href="<?php echo esc_url( $button_url ); ?>" class="inline-flex items-center gap-2 text-primary hover:text-primary-hover font-nunito font-bold text-body-1 transition-all duration-200 group">
                        <?php echo esc_html( $args['button_text'] ); ?>
                        <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </article>
    <?php
}