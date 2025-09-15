<?php
/**
 * mroomy_s functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mroomy_s
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mroomy_s_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on mroomy_s, use a find and replace
		* to change 'mroomy_s' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'mroomy_s', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'mroomy_s' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'mroomy_s_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'mroomy_s_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mroomy_s_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mroomy_s_content_width', 640 );
}
add_action( 'after_setup_theme', 'mroomy_s_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mroomy_s_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'mroomy_s' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'mroomy_s' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mroomy_s_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mroomy_s_scripts() {
	wp_enqueue_style( 'mroomy_s-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'mroomy_s-style', 'rtl', 'replace' );

	wp_enqueue_script( 'mroomy_s-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mroomy_s_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load custom navigation walkers.
 */
require get_template_directory() . '/inc/class-mroomy-nav-walker.php';
require get_template_directory() . '/inc/class-mroomy-mega-walker.php';
require get_template_directory() . '/inc/class-mroomy-mobile-walker.php';
require_once get_template_directory() . '/inc/components/button.php';
require_once get_template_directory() . '/inc/components/carousel.php';

/**
 * Enqueue scripts and styles with Vite
 */
function moj_theme_scripts() {
    // Remove default styles
    wp_dequeue_style('wp-block-library');

    // Enqueue our built assets
    if (file_exists(get_template_directory() . '/dist/main.css')) {
        wp_enqueue_style(
            'theme-style',
            get_template_directory_uri() . '/dist/main.css',
            array(),
            filemtime(get_template_directory() . '/dist/main.css')
        );
    }

    if (file_exists(get_template_directory() . '/dist/app.js')) {
        wp_enqueue_script(
            'theme-script',
            get_template_directory_uri() . '/dist/app.js',
            array(),
            filemtime(get_template_directory() . '/dist/app.js'),
            true
        );
    }

    // Enqueue top stats carousel script
    if (file_exists(get_template_directory() . '/assets/js/top-stats-carousel.js')) {
        wp_enqueue_script(
            'top-stats-carousel',
            get_template_directory_uri() . '/assets/js/top-stats-carousel.js',
            array(),
            filemtime(get_template_directory() . '/assets/js/top-stats-carousel.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'moj_theme_scripts');

/**
 * Add Tailwind classes to body
 */
function add_body_classes($classes) {
    $classes[] = 'bg-gray-50';
    return $classes;
}
add_filter('body_class', 'add_body_classes');

/**
 * Enqueue theme styles in block editor for visual parity
 */
function mroomy_s_editor_assets() {
    if (file_exists(get_template_directory() . '/dist/main.css')) {
        wp_enqueue_style(
            'theme-style-editor',
            get_template_directory_uri() . '/dist/main.css',
            array(),
            filemtime(get_template_directory() . '/dist/main.css')
        );
    }
}
add_action('enqueue_block_editor_assets', 'mroomy_s_editor_assets');

/**
 * Register Gutenberg blocks from theme
 */
function mroomy_s_register_blocks() {
    $blocks_base = get_template_directory() . '/blocks';
    if (!file_exists($blocks_base)) {
        return;
    }

    // Register top-section block if present
    $top_section = $blocks_base . '/top-section/block.json';
    if (file_exists($top_section)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[mroomy/top-section] registering block from ' . $top_section);
        }
        $render_file = $blocks_base . '/top-section/render.php';
        $render_cb = file_exists($render_file) ? include $render_file : null;
        if (is_callable($render_cb)) {
            register_block_type($top_section, array(
                'render_callback' => $render_cb,
            ));
        } else {
            register_block_type($top_section);
        }
    }

    // Register top-stats block if present
    $top_stats = $blocks_base . '/top-stats/block.json';
    if (file_exists($top_stats)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[mroomy/top-stats] registering block from ' . $top_stats);
        }
        register_block_type($top_stats);
    }
}
add_action('init', 'mroomy_s_register_blocks');

/**
 * Enable SVG uploads with security checks
 */
function mroomy_s_enable_svg_upload($mimes) {
    // Only allow SVG uploads for administrators
    if (current_user_can('administrator')) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter('upload_mimes', 'mroomy_s_enable_svg_upload');

/**
 * Fix SVG display in Media Library
 */
function mroomy_s_fix_svg_display() {
    echo '<style>
        .attachment-info .thumbnail img[src$=".svg"],
        .wp-block-image img[src$=".svg"],
        .media-modal img[src$=".svg"],
        .thumbnail img[src$=".svg"] {
            width: 100% !important;
            height: auto !important;
        }
    </style>';
}
add_action('admin_head', 'mroomy_s_fix_svg_display');

/**
 * Sanitize SVG uploads for security
 */
function mroomy_s_sanitize_svg($file) {
    // Check if file is SVG
    if ($file['type'] === 'image/svg+xml') {
        $file_content = file_get_contents($file['tmp_name']);

        // Basic security check - remove script tags
        $patterns = array(
            '/<script[\s\S]*?<\/script>/i',
            '/on[a-z]+\s*=\s*["\'][^"\']*["\']/i',
            '/<iframe[\s\S]*?<\/iframe>/i',
        );

        $clean_content = preg_replace($patterns, '', $file_content);

        // Save sanitized content back to file
        file_put_contents($file['tmp_name'], $clean_content);
    }

    return $file;
}
add_filter('wp_handle_upload_prefilter', 'mroomy_s_sanitize_svg');

/**
 * Mobile menu fallback
 */
function mroomy_s_mobile_menu_fallback() {
    echo '<ul class="space-y-1 px-6">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="block py-3 px-4 subtitle-1 text-neutral-text hover:text-primary transition-colors">Strona główna</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/o-nas' ) ) . '" class="block py-3 px-4 subtitle-1 text-neutral-text hover:text-primary transition-colors">O nas</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/produkty' ) ) . '" class="block py-3 px-4 subtitle-1 text-neutral-text hover:text-primary transition-colors">Produkty</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/kontakt' ) ) . '" class="block py-3 px-4 subtitle-1 text-neutral-text hover:text-primary transition-colors">Kontakt</a></li>';
    echo '</ul>';
}

/**
 * Rejestracja Custom Post Types dla migracji z Toolset do ACF
 * KROK 1: Rejestracja CPT
 */

/**
 * Rejestracja CPT "pokoje-dla-dzieci"
 */
function mroomy_register_pokoje_cpt() {
    $labels = array(
        'name'                  => 'Pokoje',
        'singular_name'         => 'Pokój',
        'menu_name'             => 'Pokoje dla dzieci',
        'name_admin_bar'        => 'Pokój',
        'add_new'               => 'Dodaj nowy',
        'add_new_item'          => 'Dodaj nowy pokój',
        'new_item'              => 'Nowy pokój',
        'edit_item'             => 'Edytuj pokój',
        'view_item'             => 'Zobacz pokój',
        'all_items'             => 'Wszystkie pokoje',
        'search_items'          => 'Szukaj pokoi',
        'parent_item_colon'     => 'Pokój nadrzędny:',
        'not_found'             => 'Nie znaleziono pokoi.',
        'not_found_in_trash'    => 'Nie znaleziono pokoi w koszu.',
        'featured_image'        => 'Obrazek wyróżniający pokoju',
        'set_featured_image'    => 'Ustaw obrazek wyróżniający',
        'remove_featured_image' => 'Usuń obrazek wyróżniający',
        'use_featured_image'    => 'Użyj jako obrazek wyróżniający',
        'archives'              => 'Archiwum pokoi',
        'insert_into_item'      => 'Wstaw do pokoju',
        'uploaded_to_this_item' => 'Przesłano do tego pokoju',
        'filter_items_list'     => 'Filtruj listę pokoi',
        'items_list_navigation' => 'Nawigacja listy pokoi',
        'items_list'            => 'Lista pokoi',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'pokoje-dla-dzieci' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true, // Ważne: hierarchiczny jak w Toolset
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-admin-home',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields' ),
        'show_in_rest'       => true, // Wsparcie dla edytora Gutenberg
    );

    register_post_type( 'pokoje-dla-dzieci', $args );
}
add_action( 'init', 'mroomy_register_pokoje_cpt', 0 );

/**
 * Rejestracja CPT "inspiracja"
 */
function mroomy_register_inspiracja_cpt() {
    $labels = array(
        'name'                  => 'Inspiracje',
        'singular_name'         => 'Inspiracja',
        'menu_name'             => 'Inspiracje',
        'name_admin_bar'        => 'Inspiracja',
        'add_new'               => 'Dodaj nową',
        'add_new_item'          => 'Dodaj nową inspirację',
        'new_item'              => 'Nowa inspiracja',
        'edit_item'             => 'Edytuj inspirację',
        'view_item'             => 'Zobacz inspirację',
        'all_items'             => 'Wszystkie inspiracje',
        'search_items'          => 'Szukaj inspiracji',
        'parent_item_colon'     => 'Inspiracja nadrzędna:',
        'not_found'             => 'Nie znaleziono inspiracji.',
        'not_found_in_trash'    => 'Nie znaleziono inspiracji w koszu.',
        'featured_image'        => 'Obrazek wyróżniający inspiracji',
        'set_featured_image'    => 'Ustaw obrazek wyróżniający',
        'remove_featured_image' => 'Usuń obrazek wyróżniający',
        'use_featured_image'    => 'Użyj jako obrazek wyróżniający',
        'archives'              => 'Archiwum inspiracji',
        'insert_into_item'      => 'Wstaw do inspiracji',
        'uploaded_to_this_item' => 'Przesłano do tej inspiracji',
        'filter_items_list'     => 'Filtruj listę inspiracji',
        'items_list_navigation' => 'Nawigacja listy inspiracji',
        'items_list'            => 'Lista inspiracji',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'inspiracja' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true, // Ważne: hierarchiczny jak w Toolset
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-lightbulb',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields' ),
        'show_in_rest'       => true, // Wsparcie dla edytora Gutenberg
    );

    register_post_type( 'inspiracja', $args );
}
add_action( 'init', 'mroomy_register_inspiracja_cpt', 0 );

/**
 * KROK 2: Rejestracja taksonomii
 */

/**
 * Rejestracja taksonomii "kategoria-pokoi"
 */
function mroomy_register_kategoria_pokoi_taxonomy() {
    $labels = array(
        'name'              => 'Kategorie pokoi',
        'singular_name'     => 'Kategoria pokoi',
        'search_items'      => 'Szukaj kategorii',
        'all_items'         => 'Wszystkie kategorie',
        'parent_item'       => 'Kategoria nadrzędna',
        'parent_item_colon' => 'Kategoria nadrzędna:',
        'edit_item'         => 'Edytuj kategorię',
        'update_item'       => 'Zaktualizuj kategorię',
        'add_new_item'      => 'Dodaj nową kategorię',
        'new_item_name'     => 'Nazwa nowej kategorii',
        'menu_name'         => 'Kategorie pokoi',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'kategoria-pokoi' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'kategoria-pokoi', array( 'inspiracja', 'pokoje-dla-dzieci', 'page' ), $args );
}
add_action( 'init', 'mroomy_register_kategoria_pokoi_taxonomy', 0 );

/**
 * Rejestracja taksonomii "przeznaczenie"
 */
function mroomy_register_przeznaczenie_taxonomy() {
    $labels = array(
        'name'              => 'Przeznaczenie',
        'singular_name'     => 'Przeznaczenie',
        'search_items'      => 'Szukaj przeznaczenia',
        'all_items'         => 'Wszystkie przeznaczenia',
        'parent_item'       => 'Przeznaczenie nadrzędne',
        'parent_item_colon' => 'Przeznaczenie nadrzędne:',
        'edit_item'         => 'Edytuj przeznaczenie',
        'update_item'       => 'Zaktualizuj przeznaczenie',
        'add_new_item'      => 'Dodaj nowe przeznaczenie',
        'new_item_name'     => 'Nazwa nowego przeznaczenia',
        'menu_name'         => 'Przeznaczenie',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'przeznaczenie' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'przeznaczenie', array( 'pokoje-dla-dzieci', 'page' ), $args );
}
add_action( 'init', 'mroomy_register_przeznaczenie_taxonomy', 0 );

/**
 * Rejestracja taksonomii "pokoj-na-poddaszu"
 */
function mroomy_register_pokoj_na_poddaszu_taxonomy() {
    $labels = array(
        'name'              => 'Pokój na poddaszu',
        'singular_name'     => 'Pokój na poddaszu',
        'search_items'      => 'Szukaj tagów',
        'all_items'         => 'Wszystkie tagi',
        'edit_item'         => 'Edytuj tag',
        'update_item'       => 'Zaktualizuj tag',
        'add_new_item'      => 'Dodaj nowy tag',
        'new_item_name'     => 'Nazwa nowego tagu',
        'menu_name'         => 'Pokój na poddaszu',
        'separate_items_with_commas' => 'Oddziel tagi przecinkami',
        'add_or_remove_items' => 'Dodaj lub usuń tagi',
        'choose_from_most_used' => 'Wybierz z najczęściej używanych',
    );

    $args = array(
        'hierarchical'      => false, // Płaska taksonomia
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'pokoj-na-poddaszu' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'pokoj-na-poddaszu', array( 'pokoje-dla-dzieci' ), $args );
}
add_action( 'init', 'mroomy_register_pokoj_na_poddaszu_taxonomy', 0 );

/**
 * Rejestracja taksonomii "elementy-wyposazenia"
 */
function mroomy_register_elementy_wyposazenia_taxonomy() {
    $labels = array(
        'name'              => 'Elementy wyposażenia',
        'singular_name'     => 'Element wyposażenia',
        'search_items'      => 'Szukaj elementów',
        'all_items'         => 'Wszystkie elementy',
        'edit_item'         => 'Edytuj element',
        'update_item'       => 'Zaktualizuj element',
        'add_new_item'      => 'Dodaj nowy element',
        'new_item_name'     => 'Nazwa nowego elementu',
        'menu_name'         => 'Elementy wyposażenia',
        'separate_items_with_commas' => 'Oddziel elementy przecinkami',
        'add_or_remove_items' => 'Dodaj lub usuń elementy',
        'choose_from_most_used' => 'Wybierz z najczęściej używanych',
    );

    $args = array(
        'hierarchical'      => false, // Płaska taksonomia
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'elementy-wyposazenia' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'elementy-wyposazenia', array( 'pokoje-dla-dzieci' ), $args );
}
add_action( 'init', 'mroomy_register_elementy_wyposazenia_taxonomy', 0 );

/**
 * Rejestracja taksonomii "kolorowy-sufit"
 */
function mroomy_register_kolorowy_sufit_taxonomy() {
    $labels = array(
        'name'              => 'Kolorowe sufity',
        'singular_name'     => 'Kolorowy sufit',
        'search_items'      => 'Szukaj kolorów',
        'all_items'         => 'Wszystkie kolory',
        'edit_item'         => 'Edytuj kolor',
        'update_item'       => 'Zaktualizuj kolor',
        'add_new_item'      => 'Dodaj nowy kolor',
        'new_item_name'     => 'Nazwa nowego koloru',
        'menu_name'         => 'Kolorowe sufity',
        'separate_items_with_commas' => 'Oddziel kolory przecinkami',
        'add_or_remove_items' => 'Dodaj lub usuń kolory',
        'choose_from_most_used' => 'Wybierz z najczęściej używanych',
    );

    $args = array(
        'hierarchical'      => false, // Płaska taksonomia
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'kolorowy-sufit' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'kolorowy-sufit', array( 'pokoje-dla-dzieci', 'page' ), $args );
}
add_action( 'init', 'mroomy_register_kolorowy_sufit_taxonomy', 0 );