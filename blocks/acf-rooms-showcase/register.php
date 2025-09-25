<?php
/**
 * ACF Block: Rooms Showcase
 * Registration and field definitions
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register ACF Block Type
 */
function mroomy_register_acf_rooms_showcase_block() {
    // Check if ACF function exists
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    // Register the block
    acf_register_block_type( array(
        'name'              => 'acf-rooms-showcase',
        'title'             => __( 'Pokoje - Lista', 'mroomy' ),
        'description'       => __( 'Wyświetla listę pokoi dla dzieci w formie karuzeli lub siatki', 'mroomy' ),
        'render_template'   => get_template_directory() . '/blocks/acf-rooms-showcase/render.php',
        'category'          => 'mroomy',
        'icon'              => 'admin-home',
        'keywords'          => array( 'pokoje', 'rooms', 'lista', 'karuzela', 'projekty' ),
        'mode'              => 'preview',
        'supports'          => array(
            'align'  => array( 'wide', 'full' ),
            'mode'   => true,
            'jsx'    => true,
            'anchor' => true,
            'reusable' => false, // Prevent reusable blocks to avoid cache issues
        ),
        'example'           => array(
            'attributes' => array(
                'data' => array(
                    '_is_preview' => true,
                ),
            ),
        ),
        'enqueue_style'     => get_template_directory_uri() . '/blocks/acf-rooms-showcase/editor.css',
        'enqueue_assets'    => function() {
            // Enqueue editor JavaScript for preview refresh
            wp_enqueue_script(
                'acf-rooms-showcase-editor',
                get_template_directory_uri() . '/blocks/acf-rooms-showcase/editor.js',
                array( 'acf-blocks', 'jquery' ),
                filemtime( get_template_directory() . '/blocks/acf-rooms-showcase/editor.js' ),
                true
            );
        },
    ) );
}
add_action( 'acf/init', 'mroomy_register_acf_rooms_showcase_block' );

/**
 * Register ACF Fields for Rooms Showcase Block
 */
function mroomy_register_acf_rooms_showcase_fields() {
    // Check if ACF function exists
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'      => 'group_rooms_showcase',
        'title'    => 'Ustawienia bloku Pokoje - Lista',
        'fields'   => array(
            // Tab: Ustawienia główne
            array(
                'key'   => 'field_rs_tab_main',
                'label' => 'Ustawienia główne',
                'type'  => 'tab',
            ),
            array(
                'key'           => 'field_rs_title',
                'label'         => 'Tytuł sekcji',
                'name'          => 'title',
                'type'          => 'text',
                'default_value' => 'Najlepsze projekty',
                'wrapper'       => array(
                    'width' => '100',
                ),
            ),
            array(
                'key'           => 'field_rs_button_text',
                'label'         => 'Tekst przycisku',
                'name'          => 'button_text',
                'type'          => 'text',
                'default_value' => 'Zobacz wszystkie Projekty',
                'wrapper'       => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'           => 'field_rs_button_url',
                'label'         => 'Link przycisku',
                'name'          => 'button_url',
                'type'          => 'url',
                'default_value' => '/pokoje-dla-dzieci/',
                'wrapper'       => array(
                    'width' => '50',
                ),
            ),

            // Tab: Wybór projektów
            array(
                'key'   => 'field_rs_tab_selection',
                'label' => 'Wybór projektów',
                'type'  => 'tab',
            ),
            array(
                'key'           => 'field_rs_selection_type',
                'label'         => 'Sposób wyboru projektów',
                'name'          => 'selection_type',
                'type'          => 'radio',
                'choices'       => array(
                    'latest'   => 'Najnowsze projekty',
                    'manual'   => 'Ręczny wybór',
                    'category' => 'Według kategorii',
                ),
                'default_value' => 'latest',
                'layout'        => 'vertical',
            ),
            array(
                'key'               => 'field_rs_posts_count',
                'label'             => 'Liczba projektów',
                'name'              => 'posts_count',
                'type'              => 'number',
                'default_value'     => 10,
                'min'               => 1,
                'max'               => 20,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_rs_selection_type',
                            'operator' => '==',
                            'value'    => 'latest',
                        ),
                    ),
                ),
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_rs_selected_rooms',
                'label'             => 'Wybierz projekty',
                'name'              => 'selected_rooms',
                'type'              => 'relationship',
                'post_type'         => array(
                    0 => 'pokoje-dla-dzieci',
                ),
                'filters'           => array(
                    0 => 'search',
                    1 => 'taxonomy',
                ),
                'return_format'     => 'object',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_rs_selection_type',
                            'operator' => '==',
                            'value'    => 'manual',
                        ),
                    ),
                ),
            ),
            array(
                'key'               => 'field_rs_selected_category',
                'label'             => 'Wybierz kategorię',
                'name'              => 'selected_category',
                'type'              => 'taxonomy',
                'taxonomy'          => 'przeznaczenie',
                'field_type'        => 'select',
                'return_format'     => 'id',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_rs_selection_type',
                            'operator' => '==',
                            'value'    => 'category',
                        ),
                    ),
                ),
            ),

            // Tab: Opcje wyświetlania
            array(
                'key'   => 'field_rs_tab_display',
                'label' => 'Opcje wyświetlania',
                'type'  => 'tab',
            ),
            array(
                'key'           => 'field_rs_tile_size',
                'label'         => 'Rozmiar kafelków',
                'name'          => 'tile_size',
                'type'          => 'select',
                'choices'       => array(
                    'large'  => 'Duże (386px)',
                    'medium' => 'Średnie (216px)',
                    'small'  => 'Małe (163px)',
                ),
                'default_value' => 'large',
                'wrapper'       => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'           => 'field_rs_enable_carousel',
                'label'         => 'Włącz karuzelę',
                'name'          => 'enable_carousel',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'ui_on_text'    => 'Karuzela',
                'ui_off_text'   => 'Siatka',
                'wrapper'       => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'           => 'field_rs_show_header',
                'label'         => 'Pokaż nagłówek sekcji',
                'name'          => 'show_header',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'           => 'field_rs_show_tile_buttons',
                'label'         => 'Pokaż przyciski na kafelkach',
                'name'          => 'show_tile_buttons',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'           => 'field_rs_edge_right',
                'label'         => 'Rozszerz w prawo (wyrównaj lewą krawędź do kontenera 1280px)',
                'name'          => 'edge_right',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'ui_on_text'    => 'Tak',
                'ui_off_text'   => 'Nie',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/acf-rooms-showcase',
                ),
            ),
        ),
    ) );
}
add_action( 'acf/init', 'mroomy_register_acf_rooms_showcase_fields' );