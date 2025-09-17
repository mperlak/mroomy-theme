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
        'rounded-lg',  // 16px from design tokens
        'overflow-hidden',
        'shadow-card',
        'hover:shadow-card-hover',
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
        'large'  => 'room-tile-large',
        'medium' => 'room-tile-medium',
        'small'  => 'room-tile-small'
    );
    $image_size = isset( $image_sizes[ $args['size'] ] ) ? $image_sizes[ $args['size'] ] : 'room-tile-large';

    // Determine aspect ratio based on tile size (386:491 ≈ 4:5)
    $aspect_ratios = array(
        'large'  => '386:491',  // Exact Figma ratio (szerokość:wysokość)
        'medium' => '386:491',
        'small'  => '386:491'
    );
    $aspect_ratio = isset( $aspect_ratios[ $args['size'] ] ) ? $aspect_ratios[ $args['size'] ] : '386:491';

    // Load required components
    mroomy_load_room_component( 'image' );
    mroomy_load_room_component( 'room-category-tag' );

    ?>
    <article class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <?php if ( $thumbnail_data ) : ?>
            <div class="relative rounded-lg overflow-hidden">
                <?php
                mroomy_room_image( array(
                    'image_id'     => $thumbnail_data['id'],
                    'aspect_ratio' => $aspect_ratio,
                    'size'         => $image_size,
                    'alt_text'     => $thumbnail_data['alt'] ? $thumbnail_data['alt'] : $title_data['main']
                ) );
                ?>

                <?php if ( $args['show_tags'] ) : ?>
                    <div class="absolute top-4 left-4 flex gap-3 flex-wrap">
                        <?php
                        mroomy_room_category_tags( array(
                            'post_id' => $post_id
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="p-6 flex flex-col gap-3 flex-grow">
            <?php if ( $args['show_title'] ) : ?>
                <h3 class="font-nunito font-extrabold text-[24px] leading-[30px] text-neutral-text">
                    <?php echo esc_html( $title_data['main'] ); ?>
                </h3>
            <?php endif; ?>

            <?php if ( $args['show_beneficiary'] && ! empty( $title_data['beneficiary'] ) ) : ?>
                <div class="font-nunito font-semibold text-body-1 text-neutral-text-subtlest">
                    <?php echo esc_html( $title_data['beneficiary'] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_excerpt'] && $excerpt ) : ?>
                <div class="font-nunito font-normal text-[14px] leading-[20px] text-neutral-text-subtle line-clamp-2">
                    <?php echo esc_html( $excerpt ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_actions'] ) : ?>
                <div class="mt-auto pt-2">
                    <a href="<?php echo esc_url( $button_url ); ?>" class="inline-flex items-center gap-2 text-primary hover:text-primary-hover font-nunito font-bold text-[14px] leading-[18px] transition-all duration-200 group">
                        <?php echo esc_html( $args['button_text'] ); ?>
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </article>
    <?php
}