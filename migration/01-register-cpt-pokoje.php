<?php
/**
 * Rejestracja Custom Post Types dla migracji z Toolset do ACF
 *
 * KROK 1: Rejestracja CPT
 * Dodaj ten kod do functions.php theme mroomy_s
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
 * UWAGA: Po dodaniu tego kodu do functions.php:
 *
 * 1. NAJPIERW dezaktywuj definicje CPT w Toolset Types
 *    - Idź do Toolset > Post Types
 *    - Znajdź "pokoje-dla-dzieci" i "inspiracja"
 *    - Zmień na "Managed by Types" = NIE
 *
 * 2. Odśwież permalinki
 *    - Idź do Ustawienia > Bezpośrednie odnośniki
 *    - Kliknij "Zapisz zmiany" (bez zmieniania czegokolwiek)
 *
 * 3. Sprawdź czy CPT działają:
 *    - Powinny być widoczne w menu admina
 *    - Istniejące posty powinny być nadal dostępne
 *
 * WAŻNE: Nie dezaktywuj jeszcze Toolset! Tylko wyłącz zarządzanie tymi CPT przez Toolset.
 */