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

    // Generate unique ID for this carousel instance
    $carousel_id = 'rooms-carousel-' . wp_rand( 1000, 9999 );

    // Build additional classes
    $additional_classes = ! empty( $args['class'] ) ? ' ' . $args['class'] : '';

    ?>
    <section class="py-20 relative<?php echo esc_attr( $additional_classes ); ?>">
        <div class="max-w-[1440px] mx-auto">
            <?php if ( $args['show_header'] ) : ?>
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-nunito font-extrabold text-[40px] leading-[1.25] text-neutral-text m-0">
                        <?php echo esc_html( $args['title'] ); ?>
                    </h2>

                    <a href="<?php echo esc_url( $args['button_url'] ); ?>" class="inline-flex items-center gap-2 text-primary hover:text-primary-hover font-nunito font-extrabold text-body-2 transition-all duration-200 group">
                        <?php echo esc_html( $args['button_text'] ); ?>
                        <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ( $args['enable_carousel'] ) : ?>
                <!-- Swiper container with custom CSS for peek effect -->
                <style>
                #<?php echo esc_attr( $carousel_id ); ?> {
                    margin-left: 107px;
                    width: calc(100vw - 107px);
                    max-width: none;
                }
                </style>
                <div class="swiper" id="<?php echo esc_attr( $carousel_id ); ?>">
                    <div class="swiper-wrapper">
                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                            <div class="swiper-slide !w-auto">
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

                <!-- Initialize Swiper -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const swiper_<?php echo esc_js( str_replace( '-', '_', $carousel_id ) ); ?> = new Swiper('#<?php echo esc_js( $carousel_id ); ?>', {
                        slidesPerView: 'auto',
                        spaceBetween: 24,
                        loop: false,
                        freeMode: {
                            enabled: true,
                            sticky: false
                        },
                        watchOverflow: false,
                        breakpoints: {
                            640: {
                                slidesPerView: 'auto',
                                spaceBetween: 16
                            },
                            768: {
                                slidesPerView: 'auto',
                                spaceBetween: 20
                            },
                            1024: {
                                slidesPerView: 'auto',
                                spaceBetween: 24
                            }
                        }
                    });
                });
                </script>

            <?php else : ?>
                <!-- Grid layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
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

// Swiper enqueue function has been moved to rooms-functions.php