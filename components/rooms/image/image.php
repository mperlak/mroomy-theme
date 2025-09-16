<?php
/**
 * Room Image Component
 *
 * Displays room image with aspect ratio support
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display room image with aspect ratio support
 *
 * @param array $args {
 *     Arguments for the image component.
 *
 *     @type string $image_url    URL of the image
 *     @type int    $image_id     WordPress attachment ID
 *     @type string $alt_text     Alt text for the image
 *     @type string $aspect_ratio Aspect ratio (1:1, 5:4, 4:3, 3:2, 16:9, 2:1)
 *     @type string $size         Size variant (large, medium, small)
 *     @type string $class        Additional CSS classes
 * }
 */
function mroomy_room_image( $args = array() ) {
    $defaults = array(
        'image_url'    => '',
        'image_id'     => 0,
        'alt_text'     => '',
        'aspect_ratio' => '16:9', // domyślna proporcja z Figma
        'size'         => 'large', // large, medium, small
        'class'        => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Pobierz obrazek z WordPress jeśli podano ID
    if ( $args['image_id'] ) {
        // Określ rozmiar obrazka na podstawie size
        $wp_size = 'full';
        if ( $args['size'] === 'large' ) {
            $wp_size = 'room-tile-large';
        } elseif ( $args['size'] === 'medium' ) {
            $wp_size = 'room-tile-medium';
        } elseif ( $args['size'] === 'small' ) {
            $wp_size = 'room-tile-small';
        }

        // Sprawdź czy rozmiar istnieje, jeśli nie - użyj full
        $registered_sizes = get_intermediate_image_sizes();
        if ( ! in_array( $wp_size, $registered_sizes ) && $wp_size !== 'full' ) {
            $wp_size = 'full';
        }

        $image_data = wp_get_attachment_image_src( $args['image_id'], $wp_size );
        if ( $image_data ) {
            $args['image_url'] = $image_data[0];
            $args['alt_text'] = get_post_meta( $args['image_id'], '_wp_attachment_image_alt', true );
        }
    }

    // Jeśli brak obrazka, nie renderuj
    if ( empty( $args['image_url'] ) ) {
        return;
    }

    // Mapowanie proporcji na CSS
    $aspect_ratios = array(
        '1:1'   => '1 / 1',
        '5:4'   => '5 / 4',
        '4:3'   => '4 / 3',
        '3:2'   => '3 / 2',
        '16:9'  => '16 / 9',
        '2:1'   => '2 / 1'
    );

    $css_ratio = isset( $aspect_ratios[ $args['aspect_ratio'] ] ) ? $aspect_ratios[ $args['aspect_ratio'] ] : '16 / 9';

    // Przygotuj klasy CSS
    $classes = array(
        'mroomy-room-image',
        'mroomy-room-image--' . sanitize_html_class( $args['size'] )
    );

    if ( ! empty( $args['class'] ) ) {
        $classes[] = sanitize_html_class( $args['class'] );
    }

    $class_string = implode( ' ', $classes );

    ?>
    <div class="<?php echo esc_attr( $class_string ); ?>">
        <div class="mroomy-room-image__wrapper" style="aspect-ratio: <?php echo esc_attr( $css_ratio ); ?>;">
            <?php if ( $args['image_id'] ) : ?>
                <?php
                // Użyj wp_get_attachment_image dla lepszego wsparcia srcset
                echo wp_get_attachment_image(
                    $args['image_id'],
                    $wp_size,
                    false,
                    array(
                        'class' => 'mroomy-room-image__img',
                        'alt'   => esc_attr( $args['alt_text'] )
                    )
                );
                ?>
            <?php else : ?>
                <img src="<?php echo esc_url( $args['image_url'] ); ?>"
                     alt="<?php echo esc_attr( $args['alt_text'] ); ?>"
                     class="mroomy-room-image__img"
                     loading="lazy">
            <?php endif; ?>
        </div>
    </div>
    <?php
}