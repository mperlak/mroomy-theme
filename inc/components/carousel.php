<?php
/**
 * Carousel Component
 *
 * Reusable carousel component for WordPress theme
 * Supports touch/swipe gestures and keyboard navigation
 *
 * @package mroomy_s
 */

if (!function_exists('mroomy_carousel')) {
    /**
     * Render carousel component
     *
     * @param array $args {
     *     Optional. Array of carousel arguments.
     *
     *     @type string $id           Unique ID for the carousel. Default: 'carousel-{random}'.
     *     @type array  $items        Array of HTML content for each slide.
     *     @type bool   $show_dots    Whether to show navigation dots. Default: true.
     *     @type bool   $autoplay     Whether to autoplay slides. Default: false.
     *     @type int    $autoplay_delay Delay between slides in ms. Default: 5000.
     *     @type string $class        Additional CSS classes. Default: ''.
     *     @type string $dots_size    Size of dots: 'small', 'medium', 'large'. Default: 'large'.
     * }
     * @return void
     */
    function mroomy_carousel($args = array()) {
        $defaults = array(
            'id' => 'carousel-' . wp_rand(),
            'items' => array(),
            'show_dots' => true,
            'autoplay' => false,
            'autoplay_delay' => 5000,
            'class' => '',
            'dots_size' => 'large',
        );

        $args = wp_parse_args($args, $defaults);

        if (empty($args['items'])) {
            return;
        }

        $total_items = count($args['items']);
        ?>

        <div id="<?php echo esc_attr($args['id']); ?>"
             class="mroomy-carousel <?php echo esc_attr($args['class']); ?>"
             data-autoplay="<?php echo $args['autoplay'] ? 'true' : 'false'; ?>"
             data-autoplay-delay="<?php echo esc_attr($args['autoplay_delay']); ?>">

            <!-- Carousel Container -->
            <div class="carousel-container relative overflow-hidden">
                <div class="carousel-track flex transition-transform duration-300 ease-in-out">
                    <?php foreach ($args['items'] as $index => $item) : ?>
                        <div class="carousel-slide flex-shrink-0 w-full" data-slide="<?php echo $index; ?>">
                            <?php echo $item; // Already escaped content ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($args['show_dots'] && $total_items > 1) : ?>
                <!-- Carousel Dots -->
                <div class="carousel-dots flex items-center justify-center gap-4 mt-4" role="tablist">
                    <?php for ($i = 0; $i < $total_items; $i++) : ?>
                        <?php
                        $is_active = $i === 0;
                        $dot_class = mroomy_carousel_dot_class($args['dots_size'], $is_active);
                        ?>
                        <button
                            class="carousel-dot <?php echo esc_attr($dot_class); ?>"
                            data-slide="<?php echo $i; ?>"
                            role="tab"
                            aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('Go to slide %d', 'mroomy_s'), $i + 1)); ?>">
                            <span class="sr-only"><?php echo esc_html(sprintf(__('Slide %d', 'mroomy_s'), $i + 1)); ?></span>
                        </button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php
    }
}

if (!function_exists('mroomy_carousel_dot_class')) {
    /**
     * Get carousel dot CSS classes based on size and state
     *
     * @param string $size Size of the dot ('small', 'medium', 'large')
     * @param bool $is_active Whether the dot is active
     * @return string CSS classes
     */
    function mroomy_carousel_dot_class($size = 'large', $is_active = false) {
        $classes = array('transition-all', 'duration-300');

        if ($is_active) {
            $classes[] = 'bg-primary';

            switch ($size) {
                case 'small':
                    $classes[] = 'w-3 h-1 rounded-[2px]';
                    break;
                case 'medium':
                    $classes[] = 'w-5 h-1.5 rounded-[8px]';
                    break;
                case 'large':
                default:
                    $classes[] = 'w-8 h-3 rounded-[16px]';
                    break;
            }
        } else {
            $classes[] = 'bg-neutral-field-border';

            switch ($size) {
                case 'small':
                    $classes[] = 'w-1 h-1 rounded-full';
                    break;
                case 'medium':
                    $classes[] = 'w-1.5 h-1.5 rounded-full';
                    break;
                case 'large':
                default:
                    $classes[] = 'w-3 h-3 rounded-full';
                    break;
            }
        }

        return implode(' ', $classes);
    }
}

if (!function_exists('mroomy_carousel_scripts')) {
    /**
     * Enqueue carousel scripts and styles
     */
    function mroomy_carousel_scripts() {
        // Check if carousel is used on this page
        if (has_shortcode(get_post()->post_content, 'mroomy_carousel') || is_page_template() || is_singular()) {
            wp_enqueue_script(
                'mroomy-carousel',
                get_template_directory_uri() . '/assets/js/carousel.js',
                array(),
                filemtime(get_template_directory() . '/assets/js/carousel.js'),
                true
            );
        }
    }
    add_action('wp_enqueue_scripts', 'mroomy_carousel_scripts');
}

if (!function_exists('mroomy_carousel_shortcode')) {
    /**
     * Carousel shortcode
     *
     * Usage: [mroomy_carousel show_dots="true" dots_size="large"]content[/mroomy_carousel]
     */
    function mroomy_carousel_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'id' => '',
            'show_dots' => 'true',
            'autoplay' => 'false',
            'autoplay_delay' => '5000',
            'class' => '',
            'dots_size' => 'large',
        ), $atts, 'mroomy_carousel');

        // Parse content for carousel items (separated by [slide])
        $items = array();
        if ($content) {
            $slides = explode('[slide]', $content);
            foreach ($slides as $slide) {
                $slide = trim($slide);
                if (!empty($slide)) {
                    $items[] = do_shortcode($slide);
                }
            }
        }

        ob_start();
        mroomy_carousel(array(
            'id' => $atts['id'],
            'items' => $items,
            'show_dots' => $atts['show_dots'] === 'true',
            'autoplay' => $atts['autoplay'] === 'true',
            'autoplay_delay' => intval($atts['autoplay_delay']),
            'class' => $atts['class'],
            'dots_size' => $atts['dots_size'],
        ));
        return ob_get_clean();
    }
    add_shortcode('mroomy_carousel', 'mroomy_carousel_shortcode');
}