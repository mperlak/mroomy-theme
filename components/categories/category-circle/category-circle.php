<?php
/**
 * Category Circle Component
 *
 * Displays a single category as a circular image with title
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display category circle
 *
 * @param array $args {
 *     Arguments for displaying the category circle
 *
 *     @type int    $term_id      Category term ID (required)
 *     @type string $custom_label  Custom label to override category name
 *     @type string $size          Size variant: 'desktop', 'tablet', 'mobile', 'responsive' (default: 'responsive')
 *     @type string $class         Additional CSS classes
 * }
 */
function mroomy_category_circle( $args = array() ) {
    $defaults = array(
        'term_id'      => 0,
        'custom_label' => '',
        'size'         => 'responsive',
        'class'        => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Validate term_id
    if ( ! $args['term_id'] ) {
        return;
    }

    // Get category term
    $term = get_term( $args['term_id'], 'product_cat' );

    if ( ! $term || is_wp_error( $term ) ) {
        return;
    }

    // Get category link
    $term_link = get_term_link( $term );

    if ( is_wp_error( $term_link ) ) {
        return;
    }

    // Get category name (use custom label if provided)
    $category_name = ! empty( $args['custom_label'] ) ? $args['custom_label'] : $term->name;

    // Get thumbnail URL based on size
    $thumbnail_url = '';
    $image_classes = '';
    $container_classes = 'category-circle group';

    // Size-specific classes
    switch ( $args['size'] ) {
        case 'desktop':
            $thumbnail_url = mroomy_get_category_thumbnail_url( $args['term_id'], 'category-circle' );
            $image_classes = 'w-[190px] h-[190px]';
            $text_classes = 'text-[24px] leading-[30px]';
            $gap_classes = 'gap-[24px]';
            break;

        case 'tablet':
            $thumbnail_url = mroomy_get_category_thumbnail_url( $args['term_id'], 'category-circle-tablet' );
            $image_classes = 'w-[163px] h-[163px]';
            $text_classes = 'text-[20px] leading-[26px]';
            $gap_classes = 'gap-[20px]';
            break;

        case 'mobile':
            $thumbnail_url = mroomy_get_category_thumbnail_url( $args['term_id'], 'category-circle-mobile' );
            $image_classes = 'w-[136px] h-[136px]';
            $text_classes = 'text-[17px] leading-[22px]';
            $gap_classes = 'gap-[17px]';
            break;

        case 'responsive':
        default:
            // Use largest image and let CSS handle sizing
            $thumbnail_url = mroomy_get_category_thumbnail_url( $args['term_id'], 'category-circle' );
            $image_classes = 'w-[136px] h-[136px] sm:w-[163px] sm:h-[163px] lg:w-[190px] lg:h-[190px]';
            $text_classes = 'text-[17px] leading-[22px] sm:text-[20px] sm:leading-[26px] lg:text-[24px] lg:leading-[30px]';
            $gap_classes = 'gap-[17px] sm:gap-[20px] lg:gap-[24px]';
            break;
    }

    // Add custom classes if provided
    if ( ! empty( $args['class'] ) ) {
        $container_classes .= ' ' . $args['class'];
    }

    ?>
    <article class="<?php echo esc_attr( $container_classes ); ?>">
        <a href="<?php echo esc_url( $term_link ); ?>"
           class="flex flex-col items-center <?php echo esc_attr( $gap_classes ); ?> no-underline">

            <!-- Circle image container -->
            <div class="relative rounded-full overflow-hidden <?php echo esc_attr( $image_classes ); ?> transition-all duration-200 group-hover:scale-105 group-hover:shadow-lg">
                <?php if ( $thumbnail_url ) : ?>
                    <img src="<?php echo esc_url( $thumbnail_url ); ?>"
                         alt="<?php echo esc_attr( $category_name ); ?>"
                         class="w-full h-full object-cover">
                <?php else : ?>
                    <!-- Placeholder if no image -->
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-1/3 h-1/3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                <?php endif; ?>

                <!-- Black overlay 5% -->
                <div class="absolute inset-0 bg-black/[0.05] pointer-events-none"></div>
            </div>

            <!-- Category name -->
            <h3 class="<?php echo esc_attr( $text_classes ); ?> font-nunito font-extrabold text-[#3c3c3b] text-center m-0">
                <?php echo esc_html( $category_name ); ?>
            </h3>
        </a>
    <?php
}