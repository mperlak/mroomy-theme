<?php
/**
 * Categories List Component
 *
 * Displays a list/carousel of product categories
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display categories list/carousel
 *
 * @param array $args {
 *     Optional. Arguments for displaying the categories list.
 *
 *     @type array  $categories      Array of category term IDs to display
 *     @type array  $custom_labels   Array of custom labels [term_id => label]
 *     @type string $title           Section title. Default 'Poszperaj w naszym sklepie'
 *     @type bool   $show_title      Show section title. Default true
 *     @type bool   $enable_carousel Enable carousel mode. Default true
 *     @type bool   $show_navigation Show navigation arrows. Default true
 *     @type string $class           Additional CSS classes
 *     @type string $id              Element ID
 * }
 */
function mroomy_categories_list( $args = array() ) {
    $defaults = array(
        'categories'      => array(),
        'custom_labels'   => array(),
        'title'           => 'Poszperaj w naszym sklepie',
        'show_title'      => true,
        'enable_carousel' => true,
        'show_navigation' => true,
        'class'           => '',
        'id'              => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // If no categories provided, get all top-level product categories
    if ( empty( $args['categories'] ) ) {
        $terms = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => 0
        ) );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $args['categories'] = wp_list_pluck( $terms, 'term_id' );
        }
    }

    // Exit if still no categories
    if ( empty( $args['categories'] ) ) {
        return;
    }

    // Load required components
    mroomy_load_category_component( 'category-circle' );

    // Generate unique ID for this carousel instance
    $carousel_id = 'categories-carousel-' . wp_rand( 1000, 9999 );

    // Build additional classes
    $additional_classes = ! empty( $args['class'] ) ? ' ' . $args['class'] : '';

    // Add ID if provided
    $element_id = ! empty( $args['id'] ) ? ' id="' . esc_attr( $args['id'] ) . '"' : '';

    // Check if we're in the block editor
    $is_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && isset( $_GET['context'] ) && $_GET['context'] === 'edit';
    $is_acf_preview = isset( $GLOBALS['is_preview'] ) && $GLOBALS['is_preview'];
    $disable_swiper = $is_editor || $is_acf_preview || ! $args['enable_carousel'];

    ?>
    <section class="py-[64px] sm:py-[72px] lg:py-[80px] overflow-hidden<?php echo esc_attr( $additional_classes ); ?>"<?php echo $element_id; ?>>
        <div class="container mx-auto px-4">
            <?php if ( $args['show_title'] ) : ?>
                <div class="flex justify-between items-center mb-[32px] sm:mb-[40px] lg:mb-[48px]">
                    <h2 class="font-nunito font-extrabold text-[24px] sm:text-[32px] lg:text-[40px] leading-[30px] sm:leading-[1.25] text-[#222222] m-0">
                        <?php echo esc_html( $args['title'] ); ?>
                    </h2>
                </div>
            <?php endif; ?>

            <?php if ( $args['enable_carousel'] && ! $disable_swiper ) : ?>
                <!-- Carousel mode -->
                <div class="swiper categories-swiper" id="<?php echo esc_attr( $carousel_id ); ?>">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ( $args['categories'] as $term_id ) :
                            $custom_label = isset( $args['custom_labels'][ $term_id ] ) ? $args['custom_labels'][ $term_id ] : '';
                        ?>
                            <div class="swiper-slide w-auto">
                                <?php
                                mroomy_category_circle( array(
                                    'term_id'      => $term_id,
                                    'custom_label' => $custom_label,
                                    'size'         => 'responsive'
                                ) );
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ( $args['show_navigation'] ) : ?>
                        <!-- Navigation buttons -->
                        <div class="swiper-button-prev hidden lg:flex" style="color: #e20c7b;"></div>
                        <div class="swiper-button-next hidden lg:flex" style="color: #e20c7b;"></div>
                    <?php endif; ?>
                </div>

                <!-- Initialize Swiper -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const swiper_<?php echo str_replace( '-', '_', $carousel_id ); ?> = new Swiper('#<?php echo esc_js( $carousel_id ); ?>', {
                        slidesPerView: 'auto',
                        spaceBetween: 16, // Default mobile spacing
                        loop: false,
                        watchOverflow: true,
                        enabled: true, // Enable by default, will be disabled on large screens if content fits
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
                                spaceBetween: 16,
                                enabled: true
                            },
                            // Tablet
                            640: {
                                slidesPerView: 'auto',
                                spaceBetween: 32,
                                enabled: true
                            },
                            // Desktop
                            1024: {
                                slidesPerView: 'auto',
                                spaceBetween: 64,
                                enabled: true
                            },
                            // Large desktop - disable if all items fit
                            1440: {
                                slidesPerView: 'auto',
                                spaceBetween: 64,
                                enabled: false, // Disable slider when all items fit in viewport
                                allowTouchMove: false
                            }
                        }
                    });
                });
                </script>

            <?php else : ?>
                <!-- Static grid mode (for editor or when carousel disabled) -->
                <div class="flex flex-wrap gap-[16px] sm:gap-[32px] lg:gap-[64px] <?php echo $disable_swiper ? 'justify-start' : 'justify-center'; ?>">
                    <?php
                    $max_items = $disable_swiper ? 5 : count( $args['categories'] );
                    $counter = 0;
                    foreach ( $args['categories'] as $term_id ) :
                        if ( $counter >= $max_items ) break;
                        $custom_label = isset( $args['custom_labels'][ $term_id ] ) ? $args['custom_labels'][ $term_id ] : '';
                    ?>
                        <div class="flex-shrink-0">
                            <?php
                            mroomy_category_circle( array(
                                'term_id'      => $term_id,
                                'custom_label' => $custom_label,
                                'size'         => 'responsive'
                            ) );
                            ?>
                        </div>
                    <?php
                        $counter++;
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php

    wp_reset_postdata();
}