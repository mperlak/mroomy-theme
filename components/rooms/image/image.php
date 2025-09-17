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
        'class'        => '',
        'img_class'    => ''
    );

    $args = wp_parse_args( $args, $defaults );

    // Debug - sprawdzamy co otrzymujemy
    // echo "<!-- Debug: Received size: {$args['size']} -->";

    // Określ rozmiar obrazka na podstawie size
    // Jeśli już mamy room-tile-*, użyj tego bezpośrednio
    if ( strpos( $args['size'], 'room-tile-' ) === 0 ) {
        $wp_size = $args['size'];
    } elseif ( $args['size'] === 'large' ) {
        $wp_size = 'room-tile-large';
    } elseif ( $args['size'] === 'medium' ) {
        $wp_size = 'room-tile-medium';
    } elseif ( $args['size'] === 'small' ) {
        $wp_size = 'room-tile-small';
    } else {
        $wp_size = 'full';
    }

    // Pobierz obrazek z WordPress jeśli podano ID
    if ( $args['image_id'] ) {

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

    // Mapowanie proporcji na Tailwind classes
    $aspect_classes = array(
        '1:1'     => 'aspect-square',
        '5:4'     => 'aspect-[5/4]',
        '4:5'     => 'aspect-[4/5]',
        '386:491' => 'aspect-[386/491]',  // Dokładna proporcja z Figmy
        '4:3'     => 'aspect-[4/3]',
        '3:2'     => 'aspect-[3/2]',
        '16:9'    => 'aspect-video',
        '2:1'     => 'aspect-[2/1]'
    );

    $aspect_class = isset( $aspect_classes[ $args['aspect_ratio'] ] ) ? $aspect_classes[ $args['aspect_ratio'] ] : 'aspect-video';

    // Przygotuj klasy CSS z Tailwind
    $classes = array(
        'relative',
        'overflow-hidden',
        'bg-neutral-container-bg',
        $aspect_class
    );

    if ( ! empty( $args['class'] ) ) {
        $classes[] = sanitize_html_class( $args['class'] );
    }

    $class_string = implode( ' ', $classes );

    ?>
    <div class="<?php echo esc_attr( $class_string ); ?>">
        <?php if ( $args['image_id'] ) : ?>
            <?php
            // echo "<!-- Debug: Using size: $wp_size for image ID: {$args['image_id']} -->";
            // Użyj wp_get_attachment_image dla lepszego wsparcia srcset
            echo wp_get_attachment_image(
                $args['image_id'],
                $wp_size,
                false,
                array(
                    'class' => trim( 'absolute inset-0 w-full h-full object-cover ' . $args['img_class'] ),
                    'alt'   => esc_attr( $args['alt_text'] )
                )
            );
            ?>
        <?php else : ?>
            <img src="<?php echo esc_url( $args['image_url'] ); ?>"
                 alt="<?php echo esc_attr( $args['alt_text'] ); ?>"
                 class="<?php echo esc_attr( trim( 'absolute inset-0 w-full h-full object-cover ' . $args['img_class'] ) ); ?>"
                 loading="lazy">
        <?php endif; ?>
    </div>
    <?php
}