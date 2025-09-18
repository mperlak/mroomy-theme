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
        'class'            => '',
        'is_mobile'        => false
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
        'room-tile',
        'room-tile-' . $args['size'],  // Add size variant class
        'bg-white',
        'rounded-t-lg',  // Zaokrąglenie tylko na górze
        'overflow-hidden',
        'flex',
        'flex-col',
        'h-full',
        'group'
    );

    // Add size-specific fixed width
    $size_classes = array(
        'large'  => 'w-[386px]',
        'medium' => 'w-[216px]',
        'small'  => 'w-[163px]',
        'mobile' => 'w-[216px]'  // Mobile fixed width 216px
    );
    if ( isset( $size_classes[ $args['size'] ] ) ) {
        $css_classes[] = $size_classes[ $args['size'] ];
    }

    if ( ! empty( $args['class'] ) ) {
        $css_classes[] = $args['class'];
    }

    // Determine image size based on tile size
    $image_sizes = array(
        'large'  => 'room-tile-large',
        'medium' => 'room-tile-medium',
        'small'  => 'room-tile-small',
        'mobile' => 'room-tile-mobile'  // Mobile specific size
    );
    $image_size = isset( $image_sizes[ $args['size'] ] ) ? $image_sizes[ $args['size'] ] : 'room-tile-large';

    // Determine aspect ratio based on tile size
    $aspect_ratios = array(
        'large'  => '386:491',  // Exact Figma ratio
        'medium' => '386:491',
        'small'  => '386:491',
        'mobile' => '216:283'   // Mobile ratio from Figma (216x283px)
    );
    $aspect_ratio = isset( $aspect_ratios[ $args['size'] ] ) ? $aspect_ratios[ $args['size'] ] : '386:491';

    // Load required components
    mroomy_load_room_component( 'image' );
    mroomy_load_room_component( 'room-category-tag' );

    ?>
    <article class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <?php if ( $thumbnail_data ) : ?>
            <?php
            // Mobile-specific image border radius
            $image_rounded_class = ($args['size'] === 'mobile' || $args['is_mobile'])
                ? 'rounded-[16px]'
                : 'rounded-lg';
            ?>
            <div class="<?php echo esc_attr( $image_rounded_class ); ?> overflow-hidden">
                <?php if ( $args['size'] === 'mobile' || $args['is_mobile'] ) : ?>
                    <!-- Mobile: Fixed height image -->
                    <div class="relative w-full h-[283px]">
                        <?php if ( $thumbnail_data['id'] ) : ?>
                            <?php echo wp_get_attachment_image(
                                $thumbnail_data['id'],
                                $image_size,
                                false,
                                array(
                                    'class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03] will-change-transform',
                                    'alt'   => esc_attr( $thumbnail_data['alt'] ? $thumbnail_data['alt'] : $title_data['main'] )
                                )
                            ); ?>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <!-- Desktop: Aspect ratio based -->
                    <?php
                    mroomy_room_image( array(
                        'image_id'     => $thumbnail_data['id'],
                        'aspect_ratio' => $aspect_ratio,
                        'size'         => $image_size,
                        'alt_text'     => $thumbnail_data['alt'] ? $thumbnail_data['alt'] : $title_data['main'],
                        'img_class'    => 'transition-transform duration-200 group-hover:scale-[1.03] will-change-transform'
                    ) );
                    ?>
                <?php endif; ?>

                <?php if ( $args['show_tags'] ) : ?>
                    <?php
                    // Mobile-specific tag positioning
                    $tag_position_class = ($args['size'] === 'mobile' || $args['is_mobile'])
                        ? 'absolute top-2 left-4 flex gap-2 flex-wrap'
                        : 'absolute top-4 left-4 flex gap-3 flex-wrap';
                    ?>
                    <div class="<?php echo esc_attr( $tag_position_class ); ?>">
                        <?php
                        mroomy_room_category_tags( array(
                            'post_id' => $post_id,
                            'max_tags' => ($args['size'] === 'mobile' || $args['is_mobile']) ? 2 : 3
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        // Mobile-specific spacing and gaps
        $content_classes = ($args['size'] === 'mobile' || $args['is_mobile'])
            ? 'pt-3 pb-3 px-3 flex flex-col justify-start items-start gap-2 flex-grow'
            : 'pt-4 flex flex-col justify-center items-start gap-4 flex-grow';
        ?>
        <div class="<?php echo esc_attr( $content_classes ); ?>">
            <?php if ( $args['show_title'] ) : ?>
                <?php
                // Different text sizes for different tile sizes
                if ( $args['size'] === 'mobile' || $args['is_mobile'] ) {
                    $title_classes = 'font-nunito font-extrabold text-[16px] leading-[20px] text-neutral-text'; // Mobile: 16px
                } elseif ( $args['size'] === 'medium' || $args['size'] === 'small' ) {
                    $title_classes = 'font-nunito font-extrabold text-base leading-5 text-neutral-text'; // 16px for medium/small
                } else {
                    $title_classes = 'font-nunito font-extrabold text-subtitle-2 text-neutral-text'; // 20px for large
                }
                ?>
                <h3 class="<?php echo esc_attr( $title_classes ); ?>">
                    <?php echo esc_html( $title_data['main'] ); ?>
                </h3>
            <?php endif; ?>

            <?php if ( $args['show_beneficiary'] && ! empty( $title_data['beneficiary'] ) ) : ?>
                <?php
                // Different text sizes for different tile sizes
                if ( $args['size'] === 'mobile' || $args['is_mobile'] ) {
                    $beneficiary_classes = 'font-nunito font-semibold text-[14px] leading-[18px] text-[#555555]'; // Mobile: 14px
                } elseif ( $args['size'] === 'medium' || $args['size'] === 'small' ) {
                    $beneficiary_classes = 'font-nunito font-semibold text-sm leading-[18px] text-neutral-text-subtlest'; // 14px for medium/small
                } else {
                    $beneficiary_classes = 'font-nunito font-semibold text-body-1 text-neutral-text-subtlest'; // 16px for large
                }
                ?>
                <div class="<?php echo esc_attr( $beneficiary_classes ); ?>">
                    <?php echo esc_html( $title_data['beneficiary'] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_excerpt'] && $excerpt ) : ?>
                <?php
                // Mobile-specific excerpt styles
                $excerpt_classes = ($args['size'] === 'mobile' || $args['is_mobile'])
                    ? 'font-nunito font-normal text-[14px] leading-[18px] text-[#666666] line-clamp-2'
                    : 'font-nunito font-normal text-paragraph-14-1 text-neutral-text-subtle line-clamp-2';
                ?>
                <div class="<?php echo esc_attr( $excerpt_classes ); ?>">
                    <?php echo esc_html( $excerpt ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_actions'] ) : ?>
                <?php
                // Mobile-specific button styles and sizing
                $button_container_classes = ($args['size'] === 'mobile' || $args['is_mobile'])
                    ? 'mt-auto'
                    : 'mt-auto pt-2';

                $button_classes = ($args['size'] === 'mobile' || $args['is_mobile'])
                    ? 'inline-flex items-center gap-1 text-primary hover:text-primary-hover font-nunito font-bold text-[14px] leading-[18px] transition-all duration-200 group'
                    : 'inline-flex items-center gap-2 text-primary hover:text-primary-hover font-nunito text-body-small-2 transition-all duration-200 group';

                $arrow_size = ($args['size'] === 'mobile' || $args['is_mobile'])
                    ? 'w-3 h-3'
                    : 'w-4 h-4';
                ?>
                <div class="<?php echo esc_attr( $button_container_classes ); ?>">
                    <a href="<?php echo esc_url( $button_url ); ?>" class="<?php echo esc_attr( $button_classes ); ?>">
                        <?php echo esc_html( $args['button_text'] ); ?>
                        <svg class="<?php echo esc_attr( $arrow_size ); ?> transition-transform duration-200 group-hover:translate-x-1" width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </article>
    <?php
}