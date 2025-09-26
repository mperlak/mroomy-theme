<?php
/**
 * Inspirations List Component
 *
 * Displays a list/carousel of inspiration tiles
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display inspirations list/carousel
 *
 * @param array $args {
 *     Optional. Arguments for displaying the inspirations list.
 *
 *     @type string $title         Section title. Default 'Zainspiruj się'
 *     @type string $button_text   Button text. Default 'Zobacz wszystkie Inspiracje'
 *     @type string $button_url    Button URL. Default is post type archive
 *     @type array  $post_ids      Array of specific post IDs to display
 *     @type int    $posts_per_page Number of posts to display. Default 10
 *     @type string $orderby       Order posts by. Default 'date'
 *     @type string $order         Order direction. Default 'DESC'
 *     @type array  $categories    Array of category slugs to filter by
 *     @type string $tile_size     Size of tiles: 'large', 'mobile'. Default 'large'
 *     @type bool   $enable_carousel Enable carousel mode. Default true
 *     @type bool   $show_header   Show section header. Default true
 *     @type bool   $edge_right    Enable edge-right layout. Default false
 *     @type string $class         Additional CSS classes
 *     @type string $id            Element ID
 * }
 */
function mroomy_inspirations_list( $args = array() ) {
    $defaults = array(
        'title'           => 'Zainspiruj się',
        'button_text'     => 'Zobacz wszystkie Inspiracje',
        'button_url'      => '',
        'post_ids'        => array(),
        'posts_per_page'  => 10,
        'orderby'         => 'date',
        'order'           => 'DESC',
        'categories'      => array(),
        'tile_size'       => 'large',
        'enable_carousel' => true,
        'show_header'     => true,
        'show_navigation' => true,
        'edge_right'      => false,
        'class'           => '',
        'id'              => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Get button URL if not provided
    if ( empty( $args['button_url'] ) ) {
        $args['button_url'] = get_post_type_archive_link( 'inspiracja' );
    }

    // Build query arguments
    $query_args = array(
        'post_type'      => 'inspiracja',
        'posts_per_page' => $args['posts_per_page'],
        'orderby'        => $args['orderby'],
        'order'          => $args['order'],
        'post_status'    => 'publish'
        // Removed meta_query for _thumbnail_id as we're using ACF field 'header_picture'
    );

    // Add specific post IDs if provided
    if ( ! empty( $args['post_ids'] ) ) {
        $query_args['post__in'] = $args['post_ids'];
        $query_args['orderby'] = 'post__in';
    }

    // Add category filter if provided
    if ( ! empty( $args['categories'] ) ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'kategoria-pokoi',
                'field'    => 'slug',
                'terms'    => $args['categories']
            )
        );
    }

    // Run the query
    $query = new WP_Query( $query_args );

    if ( ! $query->have_posts() ) {
        return;
    }

    // Load required components
    mroomy_load_inspiration_component( 'inspiration-tile' );

    // Generate unique ID for this carousel instance
    $carousel_id = 'inspirations-carousel-' . wp_rand( 1000, 9999 );

    // Build additional classes
    $additional_classes = ! empty( $args['class'] ) ? ' ' . $args['class'] : '';

    // Add ID if provided
    $element_id = ! empty( $args['id'] ) ? ' id="' . esc_attr( $args['id'] ) . '"' : '';

    $edge_right = ! empty( $args['edge_right'] );

    ?>
    <section class="py-12 sm:py-16 lg:py-20 overflow-hidden<?php echo esc_attr( $additional_classes ); ?>"<?php echo $element_id; ?>>
        <?php if ( $edge_right ) : ?>
        <div class="rooms-edge-right">
            <?php if ( $args['show_header'] ) : ?>
                <!-- Mobile header -->
                <div class="rooms-edge-right__pl-pr flex justify-between items-center mb-6 lg:mb-8">
                    <h2 class="font-nunito font-extrabold text-[24px] sm:text-[32px] lg:text-[40px] leading-[30px] sm:leading-[1.25] text-neutral-text m-0">
                        <?php echo esc_html( $args['title'] ); ?>
                    </h2>

                    <!-- Desktop button -->
                    <a href="<?php echo esc_url( $args['button_url'] ); ?>" class="hidden sm:inline-flex items-center gap-2 text-primary hover:text-primary-hover font-nunito font-extrabold text-body-2 transition-all duration-200 group">
                        <?php echo esc_html( $args['button_text'] ); ?>
                        <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <!-- Mobile "Więcej" button -->
                    <a href="<?php echo esc_url( $args['button_url'] ); ?>" class="sm:hidden inline-flex items-center gap-1 text-primary hover:text-primary-hover font-nunito font-extrabold text-[16px] leading-[20px] transition-all duration-200 group">
                        <span class="px-1">Więcej</span>
                        <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ( $args['enable_carousel'] ) : ?>
                <?php
                // Check if we're in the block editor
                $is_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && isset( $_GET['context'] ) && $_GET['context'] === 'edit';
                $is_acf_preview = isset( $GLOBALS['is_preview'] ) && $GLOBALS['is_preview'];
                $disable_swiper = $is_editor || $is_acf_preview;
                $preview_limit = $disable_swiper ? 4 : PHP_INT_MAX;
                $count = 0;
                ?>
                <!-- Swiper container aligned to edge-right -->
                <div class="rooms-edge-right__pl">
                    <div class="<?php echo $disable_swiper ? 'editor-carousel editor-carousel-grid' : 'swiper'; ?> px-0" id="<?php echo esc_attr( $carousel_id ); ?>">
                        <div class="<?php echo $disable_swiper ? 'editor-carousel-wrapper' : 'swiper-wrapper'; ?>">
                            <?php
                            while ( $query->have_posts() && $count < $preview_limit ) :
                                $query->the_post();
                                $count++;
                            ?>
                                <div class="<?php echo $disable_swiper ? 'editor-slide' : 'swiper-slide'; ?> w-auto">
                                    <?php
                                    mroomy_inspiration_tile( array(
                                        'post_id' => get_the_ID(),
                                        'size'    => wp_is_mobile() ? 'mobile' : $args['tile_size']
                                    ) );
                                    ?>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <?php if ( ! $disable_swiper && $args['show_navigation'] ) : ?>
                            <!-- Navigation buttons -->
                            <div class="swiper-button-prev hidden lg:flex" style="color: #e20c7b;"></div>
                            <div class="swiper-button-next hidden lg:flex" style="color: #e20c7b;"></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else : ?>
                <!-- No edge right, standard container -->
                <div class="container mx-auto px-4">
                    <?php if ( $args['enable_carousel'] ) : ?>
                        <div class="swiper px-0" id="<?php echo esc_attr( $carousel_id ); ?>">
                            <div class="swiper-wrapper">
                                <?php
                                while ( $query->have_posts() ) :
                                    $query->the_post();
                                ?>
                                    <div class="swiper-slide w-auto">
                                        <?php
                                        mroomy_inspiration_tile( array(
                                            'post_id' => get_the_ID(),
                                            'size'    => wp_is_mobile() ? 'mobile' : $args['tile_size']
                                        ) );
                                        ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php else : ?>
            <!-- Standard layout without edge-right -->
            <div class="container mx-auto px-4">
                <?php if ( $args['show_header'] ) : ?>
                    <div class="flex justify-between items-center mb-6 lg:mb-8">
                        <h2 class="font-nunito font-extrabold text-[24px] sm:text-[32px] lg:text-[40px] leading-[30px] sm:leading-[1.25] text-neutral-text m-0">
                            <?php echo esc_html( $args['title'] ); ?>
                        </h2>

                        <!-- Desktop button -->
                        <a href="<?php echo esc_url( $args['button_url'] ); ?>" class="hidden sm:inline-flex items-center gap-2 text-primary hover:text-primary-hover font-nunito font-extrabold text-body-2 transition-all duration-200 group">
                            <?php echo esc_html( $args['button_text'] ); ?>
                            <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

                        <!-- Mobile "Więcej" button -->
                        <a href="<?php echo esc_url( $args['button_url'] ); ?>" class="sm:hidden inline-flex items-center gap-1 text-primary hover:text-primary-hover font-nunito font-extrabold text-[16px] leading-[20px] transition-all duration-200 group">
                            <span class="px-1">Więcej</span>
                            <svg class="w-6 h-6 transition-transform duration-200 group-hover:translate-x-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( $args['enable_carousel'] ) : ?>
                    <?php
                    // Check if we're in the block editor
                    $is_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && isset( $_GET['context'] ) && $_GET['context'] === 'edit';
                    $is_acf_preview = isset( $GLOBALS['is_preview'] ) && $GLOBALS['is_preview'];
                    $disable_swiper = $is_editor || $is_acf_preview;
                    $preview_limit = $disable_swiper ? 4 : PHP_INT_MAX;
                    $count = 0;
                    ?>
                    <div class="<?php echo $disable_swiper ? 'editor-carousel' : 'swiper'; ?>" id="<?php echo esc_attr( $carousel_id ); ?>">
                        <div class="<?php echo $disable_swiper ? 'flex gap-4 flex-wrap' : 'swiper-wrapper'; ?>">
                            <?php
                            while ( $query->have_posts() && $count < $preview_limit ) :
                                $query->the_post();
                                $count++;
                            ?>
                                <div class="<?php echo $disable_swiper ? '' : 'swiper-slide w-auto'; ?>">
                                    <?php
                                    mroomy_inspiration_tile( array(
                                        'post_id' => get_the_ID(),
                                        'size'    => wp_is_mobile() ? 'mobile' : $args['tile_size']
                                    ) );
                                    ?>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <?php if ( ! $disable_swiper && $args['show_navigation'] ) : ?>
                            <!-- Navigation buttons -->
                            <div class="swiper-button-prev hidden lg:flex" style="color: #e20c7b;"></div>
                            <div class="swiper-button-next hidden lg:flex" style="color: #e20c7b;"></div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <!-- Grid layout without carousel -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                        <?php
                        while ( $query->have_posts() ) :
                            $query->the_post();
                        ?>
                            <?php
                            mroomy_inspiration_tile( array(
                                'post_id' => get_the_ID(),
                                'size'    => wp_is_mobile() ? 'mobile' : $args['tile_size']
                            ) );
                            ?>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

        <?php if ( $args['enable_carousel'] && ! $disable_swiper ) : ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const swiper_<?php echo str_replace( '-', '_', $carousel_id ); ?> = new Swiper('#<?php echo esc_js( $carousel_id ); ?>', {
                    slidesPerView: 'auto',
                    spaceBetween: 16, // Default mobile spacing
                    loop: false,
                    watchOverflow: true,
                    freeMode: {
                        enabled: true,
                        sticky: true
                    },
                    mousewheel: {
                        forceToAxis: true,
                        sensitivity: 1,
                        releaseOnEdges: false
                    },
                    <?php if ( $args['show_navigation'] ) : ?>
                    navigation: {
                        nextEl: '#<?php echo esc_js( $carousel_id ); ?> .swiper-button-next',
                        prevEl: '#<?php echo esc_js( $carousel_id ); ?> .swiper-button-prev',
                    },
                    <?php endif; ?>
                    breakpoints: {
                        // Mobile
                        0: {
                            slidesPerView: 'auto',
                            spaceBetween: 16
                        },
                        // Tablet
                        640: {
                            slidesPerView: 'auto',
                            spaceBetween: 24
                        },
                        // Desktop
                        1024: {
                            slidesPerView: 'auto',
                            spaceBetween: 32
                        }
                    }
                });
            });
            </script>
        <?php endif; ?>
    </section>
    <?php
}