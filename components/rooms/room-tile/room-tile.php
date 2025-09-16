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

    // Determine size classes
    $size_classes = array(
        'large'  => 'mroomy-room-tile--large',
        'medium' => 'mroomy-room-tile--medium',
        'small'  => 'mroomy-room-tile--small'
    );
    $size_class = isset( $size_classes[ $args['size'] ] ) ? $size_classes[ $args['size'] ] : $size_classes['large'];

    // Build CSS classes
    $css_classes = array(
        'mroomy-room-tile',
        $size_class
    );

    if ( ! empty( $args['class'] ) ) {
        $css_classes[] = $args['class'];
    }

    // Determine image size based on tile size
    $image_sizes = array(
        'large'  => 'large',
        'medium' => 'medium',
        'small'  => 'small'
    );
    $image_size = isset( $image_sizes[ $args['size'] ] ) ? $image_sizes[ $args['size'] ] : 'large';

    // Determine aspect ratio based on tile size
    $aspect_ratios = array(
        'large'  => '4:3',
        'medium' => '4:3',
        'small'  => '4:3'
    );
    $aspect_ratio = isset( $aspect_ratios[ $args['size'] ] ) ? $aspect_ratios[ $args['size'] ] : '4:3';

    // Load required components
    mroomy_load_room_component( 'image' );
    mroomy_load_room_component( 'room-category-tag' );

    ?>
    <article class="<?php echo esc_attr( implode( ' ', array_filter( $css_classes ) ) ); ?>">
        <?php if ( $thumbnail_data ) : ?>
            <div class="mroomy-room-tile__image">
                <?php
                mroomy_room_image( array(
                    'image_id'     => $thumbnail_data['id'],
                    'aspect_ratio' => $aspect_ratio,
                    'size'         => $image_size,
                    'alt_text'     => $thumbnail_data['alt'] ? $thumbnail_data['alt'] : $title_data['main']
                ) );
                ?>

                <?php if ( $args['show_tags'] ) : ?>
                    <div class="mroomy-room-tile__tags">
                        <?php
                        mroomy_room_category_tags( array(
                            'post_id' => $post_id,
                            'class'   => 'mroomy-room-tile__category-tags'
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mroomy-room-tile__content">
            <?php if ( $args['show_title'] ) : ?>
                <h3 class="mroomy-room-tile__title">
                    <a href="<?php echo esc_url( $button_url ); ?>" class="mroomy-room-tile__title-link">
                        <?php echo esc_html( $title_data['main'] ); ?>
                    </a>
                </h3>
            <?php endif; ?>

            <?php if ( $args['show_excerpt'] && $excerpt ) : ?>
                <div class="mroomy-room-tile__description">
                    <?php echo esc_html( $excerpt ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_beneficiary'] && ! empty( $title_data['beneficiary'] ) ) : ?>
                <div class="mroomy-room-tile__beneficiary">
                    <?php echo esc_html( $title_data['beneficiary'] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $args['show_actions'] ) : ?>
                <div class="mroomy-room-tile__actions">
                    <a href="<?php echo esc_url( $button_url ); ?>" class="mroomy-room-tile__button">
                        <?php echo esc_html( $args['button_text'] ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </article>
    <?php
}