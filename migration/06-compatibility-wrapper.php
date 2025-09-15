<?php
/**
 * Wrapper functions dla kompatybilności Toolset → ACF
 * Dodaj do functions.php jeśli używasz funkcji Toolset w szablonach
 */

/**
 * Wrapper dla types_render_field()
 * Emuluje zachowanie Toolset używając ACF
 */
if (!function_exists('types_render_field')) {
    function types_render_field($field_name, $args = array()) {
        // Usuń prefix wpcf- jeśli istnieje
        $field_name = str_replace('wpcf-', '', $field_name);

        // Mapowanie nazw pól Toolset na ACF
        $field_map = [
            // Pokoje
            'sku' => 'sku',
            'header' => 'header',
            'krotki-opis' => 'krotki_opis',
            'dlugi-opis' => 'dlugi_opis',
            'opis' => 'opis',
            'krotki' => 'krotki',
            'wymiary' => 'wymiary',
            'kolor' => 'kolor',
            'material' => 'material',
            'dodatkowe-informacje' => 'dodatkowe_informacje',
            'czas-dostawy' => 'czas_dostawy',
            'na-zamowienie' => 'na_zamowienie',
            'header-text' => 'header_text',
            'subheader-text' => 'subheader_text',
            'header-picture' => 'header_picture',
            'wizualizacje' => 'wizualizacje',
            'zdjecia' => 'zdjecia',
            // Inspiracje
            'dodatkowy-opis-indodatkowy-opis-inspiracjaspiracja' => 'dodatkowy_opis_inspiracja',
        ];

        // Pobierz nazwę pola ACF
        $acf_field = isset($field_map[$field_name]) ? $field_map[$field_name] : $field_name;

        // Pobierz post ID
        $post_id = isset($args['id']) ? $args['id'] : get_the_ID();

        // Pobierz wartość z ACF
        $value = get_field($acf_field, $post_id);

        // Obsługa różnych typów pól
        if ($field_name === 'wizualizacje' || $field_name === 'zdjecia') {
            // Galeria - zwróć URLs
            if (is_array($value)) {
                $output = '';
                foreach ($value as $image) {
                    if (isset($args['size'])) {
                        $url = $image['sizes'][$args['size']] ?? $image['url'];
                    } else {
                        $url = $image['url'];
                    }

                    if (isset($args['output']) && $args['output'] === 'raw') {
                        return $url; // Zwróć tylko pierwszy URL
                    }

                    $output .= '<img src="' . esc_url($url) . '" alt="' . esc_attr($image['alt']) . '" />';
                }
                return $output;
            }
        }

        if ($field_name === 'header-picture') {
            // Pojedynczy obraz
            if (is_array($value)) {
                if (isset($args['output']) && $args['output'] === 'raw') {
                    return $value['url'];
                }
                return '<img src="' . esc_url($value['url']) . '" alt="' . esc_attr($value['alt']) . '" />';
            }
        }

        if ($field_name === 'na-zamowienie') {
            // Checkbox/True-False
            return $value ? '1' : '0';
        }

        // Domyślnie zwróć wartość
        return $value;
    }
}

/**
 * Wrapper dla get_post_meta z prefiksem wpcf-
 * Przekieruj na ACF
 */
add_filter('get_post_metadata', 'toolset_to_acf_metadata_wrapper', 10, 4);
function toolset_to_acf_metadata_wrapper($value, $post_id, $meta_key, $single) {
    // Sprawdź czy to pole Toolset
    if (strpos($meta_key, 'wpcf-') !== 0) {
        return $value;
    }

    // Usuń prefix
    $field_name = str_replace('wpcf-', '', $meta_key);

    // Mapowanie pól
    $field_map = [
        'sku' => 'sku',
        'header' => 'header',
        'krotki-opis' => 'krotki_opis',
        'dlugi-opis' => 'dlugi_opis',
        'opis' => 'opis',
        'krotki' => 'krotki',
        'wymiary' => 'wymiary',
        'kolor' => 'kolor',
        'material' => 'material',
        'dodatkowe-informacje' => 'dodatkowe_informacje',
        'czas-dostawy' => 'czas_dostawy',
        'na-zamowienie' => 'na_zamowienie',
        'header-text' => 'header_text',
        'subheader-text' => 'subheader_text',
        'header-picture' => 'header_picture',
        'wizualizacje' => 'wizualizacje',
        'zdjecia' => 'zdjecia',
    ];

    if (isset($field_map[$field_name])) {
        $acf_value = get_field($field_map[$field_name], $post_id);

        // Obsługa galerii - zwróć URLs
        if (in_array($field_name, ['wizualizacje', 'zdjecia']) && is_array($acf_value)) {
            $urls = [];
            foreach ($acf_value as $image) {
                $urls[] = $image['url'];
            }
            return $single ? $urls[0] : $urls;
        }

        // Obsługa obrazu
        if ($field_name === 'header-picture' && is_array($acf_value)) {
            return $acf_value['url'];
        }

        return $acf_value;
    }

    return $value;
}

/**
 * Helper do sprawdzenia czy używamy ACF czy Toolset
 */
function is_using_acf() {
    return class_exists('ACF');
}

/**
 * Helper do pobrania pola niezależnie od systemu
 */
function get_custom_field($field_name, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Spróbuj ACF
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        if ($value !== false) {
            return $value;
        }
    }

    // Spróbuj Toolset
    $toolset_field = 'wpcf-' . $field_name;
    $value = get_post_meta($post_id, $toolset_field, true);

    return $value;
}