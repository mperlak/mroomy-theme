<?php
/**
 * Rooms List Component
 *
 * Displays a list/carousel of room tiles
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display rooms list/carousel
 *
 * @param array $args {
 *     Optional. Arguments for displaying the rooms list.
 *
 *     @type string $title         Section title. Default 'Najlepsze projekty'
 *     @type string $button_text   Button text. Default 'Zobacz wszystkie Projekty'
 *     @type string $button_url    Button URL. Default is post type archive
 *     @type array  $post_ids      Array of specific post IDs to display
 *     @type int    $posts_per_page Number of posts to display. Default 12
 *     @type string $orderby       Order posts by. Default 'date'
 *     @type string $order         Order direction. Default 'DESC'
 *     @type array  $categories    Array of category slugs to filter by
 *     @type string $tile_size     Size of tiles: 'large', 'medium', 'small'. Default 'large'
 *     @type bool   $enable_carousel Enable carousel mode. Default true
 *     @type bool   $show_header   Show section header. Default true
 *     @type string $class         Additional CSS classes
 * }
 */
function mroomy_rooms_list( $args = array() ) {
    $defaults = array(
        'title'           => 'Najlepsze projekty',
        'button_text'     => 'Zobacz wszystkie Projekty',
        'button_url'      => '',
        'post_ids'        => array(),
        'posts_per_page'  => 12,
        'orderby'         => 'date',
        'order'           => 'DESC',
        'categories'      => array(),
        'tile_size'       => 'large',
        'enable_carousel' => true,
        'show_header'     => true,
        'class'           => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Get button URL if not provided
    if ( empty( $args['button_url'] ) ) {
        $args['button_url'] = get_post_type_archive_link( 'pokoje-dla-dzieci' );
    }

    // Build query arguments
    $query_args = array(
        'post_type'      => 'pokoje-dla-dzieci',
        'posts_per_page' => $args['posts_per_page'],
        'orderby'        => $args['orderby'],
        'order'          => $args['order'],
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
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
                'taxonomy' => 'przeznaczenie',
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
    mroomy_load_room_component( 'room-tile' );

    // Build CSS classes
    $css_classes = array(
        'mroomy-rooms-list'
    );

    if ( $args['enable_carousel'] ) {
        $css_classes[] = 'mroomy-rooms-list--carousel';
    }

    if ( ! empty( $args['class'] ) ) {
        $css_classes[] = $args['class'];
    }

    // Generate unique ID for this carousel instance
    $carousel_id = 'rooms-carousel-' . wp_rand( 1000, 9999 );

    ?>
    <section class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <?php if ( $args['show_header'] ) : ?>
            <div class="mroomy-rooms-list__header">
                <h2 class="mroomy-rooms-list__title"><?php echo esc_html( $args['title'] ); ?></h2>

                <a href="<?php echo esc_url( $args['button_url'] ); ?>" class="mroomy-rooms-list__button">
                    <?php echo esc_html( $args['button_text'] ); ?>
                    <svg class="mroomy-rooms-list__button-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>

        <div class="mroomy-rooms-list__container">
            <?php if ( $args['enable_carousel'] ) : ?>
                <!-- Swiper container -->
                <div class="swiper" id="<?php echo esc_attr( $carousel_id ); ?>">
                    <div class="swiper-wrapper">
                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                            <div class="swiper-slide">
                                <?php
                                mroomy_room_tile( array(
                                    'post_id' => get_the_ID(),
                                    'size'    => $args['tile_size']
                                ) );
                                ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Navigation buttons -->
                <div class="mroomy-rooms-list__nav">
                    <button class="mroomy-rooms-list__nav-prev" id="<?php echo esc_attr( $carousel_id ); ?>-prev" aria-label="Previous slide">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="mroomy-rooms-list__nav-next" id="<?php echo esc_attr( $carousel_id ); ?>-next" aria-label="Next slide">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

                <!-- Initialize Swiper -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const swiper_<?php echo esc_js( str_replace( '-', '_', $carousel_id ) ); ?> = new Swiper('#<?php echo esc_js( $carousel_id ); ?>', {
                        slidesPerView: 1,
                        spaceBetween: 24,
                        loop: false,
                        navigation: {
                            nextEl: '#<?php echo esc_js( $carousel_id ); ?>-next',
                            prevEl: '#<?php echo esc_js( $carousel_id ); ?>-prev',
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 24
                            },
                            1024: {
                                slidesPerView: 4,
                                spaceBetween: 24
                            }
                        }
                    });
                });
                </script>

            <?php else : ?>
                <!-- Grid layout -->
                <div class="mroomy-rooms-list__grid">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <?php
                        mroomy_room_tile( array(
                            'post_id' => get_the_ID(),
                            'size'    => $args['tile_size']
                        ) );
                        ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php

    wp_reset_postdata();
}

/**
 * Enqueue Swiper assets
 */
function mroomy_enqueue_swiper_assets() {
    if ( is_singular( 'pokoje-dla-dzieci' ) || has_block( 'mroomy/rooms-showcase' ) || is_post_type_archive( 'pokoje-dla-dzieci' ) ) {
        // Enqueue Swiper CSS
        wp_enqueue_style(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.0'
        );

        // Enqueue Swiper JS
        wp_enqueue_script(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.0',
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'mroomy_enqueue_swiper_assets' );