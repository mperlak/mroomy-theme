<?php
/**
 * ACF Block: Inspirations Showcase
 * Registration file
 *
 * @package Mroomy
 */

// Zabezpieczenie
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register ACF Block Type for Inspirations Showcase
 */
function mroomy_register_inspirations_showcase_block() {
    // Check if ACF Pro is active
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    // Register the block
    acf_register_block_type( array(
        'name'              => 'acf-inspirations-showcase',
        'title'             => __( 'Inspirations Showcase', 'mroomy' ),
        'description'       => __( 'Wyświetla karuzelę inspiracji', 'mroomy' ),
        'render_template'   => get_template_directory() . '/blocks/acf-inspirations-showcase/render.php',
        'category'          => 'mroomy',
        'icon'              => 'images-alt2',
        'keywords'          => array( 'inspiracje', 'inspirations', 'showcase', 'carousel', 'karuzela' ),
        'supports'          => array(
            'align'     => array( 'wide', 'full' ),
            'anchor'    => true,
            'className' => true,
            'mode'      => true,
            'jsx'       => true
        ),
        'example'           => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => array(
                    'title'           => 'Zainspiruj się',
                    'button_text'     => 'Zobacz wszystkie Inspiracje',
                    'selection_type'  => 'latest',
                    'posts_count'     => 6,
                    'enable_carousel' => true,
                    'show_header'     => true,
                    '_is_preview'     => true
                )
            )
        ),
        'enqueue_assets'    => function() {
            // Swiper is already enqueued by rooms-functions.php
            // Just ensure it detects our block
        }
    ) );
}
add_action( 'acf/init', 'mroomy_register_inspirations_showcase_block' );

/**
 * Register ACF Fields for the Inspirations Showcase block
 */
function mroomy_register_inspirations_showcase_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'      => 'group_inspirations_showcase_block',
        'title'    => 'Inspirations Showcase Block',
        'fields'   => array(
            // Title field
            array(
                'key'               => 'field_inspirations_title',
                'label'             => 'Tytuł sekcji',
                'name'              => 'title',
                'type'              => 'text',
                'instructions'      => 'Tytuł wyświetlany nad karuzelą',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '50',
                    'class' => '',
                    'id'    => '',
                ),
                'default_value'     => 'Zainspiruj się',
                'placeholder'       => '',
                'prepend'           => '',
                'append'            => '',
                'maxlength'         => '',
            ),
            // Button text field
            array(
                'key'               => 'field_inspirations_button_text',
                'label'             => 'Tekst przycisku',
                'name'              => 'button_text',
                'type'              => 'text',
                'instructions'      => 'Tekst wyświetlany na przycisku',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '50',
                    'class' => '',
                    'id'    => '',
                ),
                'default_value'     => 'Zobacz wszystkie Inspiracje',
                'placeholder'       => '',
                'prepend'           => '',
                'append'            => '',
                'maxlength'         => '',
            ),
            // Button URL field
            array(
                'key'               => 'field_inspirations_button_url',
                'label'             => 'URL przycisku',
                'name'              => 'button_url',
                'type'              => 'url',
                'instructions'      => 'Opcjonalny link dla przycisku. Jeśli pusty, używa archiwum inspiracji.',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'default_value'     => '',
                'placeholder'       => '',
            ),
            // Selection type
            array(
                'key'               => 'field_inspirations_selection_type',
                'label'             => 'Typ selekcji',
                'name'              => 'selection_type',
                'type'              => 'select',
                'instructions'      => 'Wybierz sposób wyboru inspiracji',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'choices'           => array(
                    'latest'   => 'Najnowsze',
                    'manual'   => 'Wybrane ręcznie',
                    'random'   => 'Losowe',
                ),
                'default_value'     => 'latest',
                'allow_null'        => 0,
                'multiple'          => 0,
                'ui'                => 0,
                'return_format'     => 'value',
                'ajax'              => 0,
                'placeholder'       => '',
            ),
            // Posts count (for latest)
            array(
                'key'               => 'field_inspirations_posts_count',
                'label'             => 'Liczba inspiracji',
                'name'              => 'posts_count',
                'type'              => 'number',
                'instructions'      => 'Ile inspiracji wyświetlić',
                'required'          => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_inspirations_selection_type',
                            'operator' => '!=',
                            'value'    => 'manual',
                        ),
                    ),
                ),
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'default_value'     => 10,
                'placeholder'       => '',
                'prepend'           => '',
                'append'            => '',
                'min'               => 1,
                'max'               => 30,
                'step'              => 1,
            ),
            // Selected inspirations (for manual)
            array(
                'key'               => 'field_inspirations_selected',
                'label'             => 'Wybrane inspiracje',
                'name'              => 'selected_inspirations',
                'type'              => 'relationship',
                'instructions'      => 'Wybierz inspiracje do wyświetlenia',
                'required'          => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_inspirations_selection_type',
                            'operator' => '==',
                            'value'    => 'manual',
                        ),
                    ),
                ),
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'post_type'         => array(
                    0 => 'inspiracja',
                ),
                'taxonomy'          => '',
                'filters'           => array(
                    0 => 'search',
                    1 => 'taxonomy',
                ),
                'elements'          => array(
                    0 => 'featured_image',
                ),
                'min'               => '',
                'max'               => '',
                'return_format'     => 'id',
            ),
            // Enable carousel
            array(
                'key'               => 'field_inspirations_enable_carousel',
                'label'             => 'Włącz karuzelę',
                'name'              => 'enable_carousel',
                'type'              => 'true_false',
                'instructions'      => 'Wyświetl inspiracje jako karuzelę',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '33',
                    'class' => '',
                    'id'    => '',
                ),
                'message'           => '',
                'default_value'     => 1,
                'ui'                => 1,
                'ui_on_text'        => '',
                'ui_off_text'       => '',
            ),
            // Show header
            array(
                'key'               => 'field_inspirations_show_header',
                'label'             => 'Pokaż nagłówek',
                'name'              => 'show_header',
                'type'              => 'true_false',
                'instructions'      => 'Wyświetl tytuł i przycisk',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '33',
                    'class' => '',
                    'id'    => '',
                ),
                'message'           => '',
                'default_value'     => 1,
                'ui'                => 1,
                'ui_on_text'        => '',
                'ui_off_text'       => '',
            ),
            // Edge right
            array(
                'key'               => 'field_inspirations_edge_right',
                'label'             => 'Wyrównanie do prawej krawędzi',
                'name'              => 'edge_right',
                'type'              => 'true_false',
                'instructions'      => 'Rozciągnij karuzelę do prawej krawędzi ekranu',
                'required'          => 0,
                'conditional_logic' => 0,
                'wrapper'           => array(
                    'width' => '34',
                    'class' => '',
                    'id'    => '',
                ),
                'message'           => '',
                'default_value'     => 0,
                'ui'                => 1,
                'ui_on_text'        => '',
                'ui_off_text'       => '',
            ),
            // Show navigation arrows
            array(
                'key'               => 'field_inspirations_show_navigation',
                'label'             => 'Pokaż strzałki nawigacji',
                'name'              => 'show_navigation',
                'type'              => 'true_false',
                'instructions'      => 'Wyświetl strzałki do przewijania karuzeli (tylko desktop)',
                'required'          => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_inspirations_enable_carousel',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
                'wrapper'           => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'message'           => '',
                'default_value'     => 1,
                'ui'                => 1,
                'ui_on_text'        => '',
                'ui_off_text'       => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/acf-inspirations-showcase',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => true,
        'description'           => '',
        'show_in_rest'          => 0,
    ) );
}
add_action( 'acf/init', 'mroomy_register_inspirations_showcase_fields' );