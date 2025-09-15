<?php
/**
 * Skrypt migracji danych z Toolset do ACF
 *
 * UWAGA: Uruchom ten skrypt TYLKO RAZ!
 * Najlepiej przez WP-CLI: wp eval-file migration/05-migrate-data.php
 * Lub utworz tymczasową stronę admina
 */

// Zabezpieczenie przed przypadkowym uruchomieniem
if (!defined('WP_CLI') && !current_user_can('manage_options')) {
    die('Brak uprawnień');
}

class Toolset_To_ACF_Migration {

    private $field_mapping = [
        // Mapowanie pól dla pokoje-dla-dzieci
        'pokoje' => [
            'wpcf-sku' => 'field_pokoje_sku',
            'wpcf-header' => 'field_pokoje_header',
            'wpcf-krotki-opis' => 'field_pokoje_krotki_opis',
            'wpcf-dlugi-opis' => 'field_pokoje_dlugi_opis',
            'wpcf-opis' => 'field_pokoje_opis',
            'wpcf-krotki' => 'field_pokoje_krotki',
            'wpcf-wymiary' => 'field_pokoje_wymiary',
            'wpcf-kolor' => 'field_pokoje_kolor',
            'wpcf-material' => 'field_pokoje_material',
            'wpcf-dodatkowe-informacje' => 'field_pokoje_dodatkowe_informacje',
            'wpcf-czas-dostawy' => 'field_pokoje_czas_dostawy',
            'wpcf-na-zamowienie' => 'field_pokoje_na_zamowienie',
            'wpcf-header-text' => 'field_pokoje_header_text',
            'wpcf-subheader-text' => 'field_pokoje_subheader_text',
            'wpcf-header-picture' => 'field_pokoje_header_picture',
            // Pola repetitive - specjalna obsługa
            'wpcf-wizualizacje' => 'field_pokoje_wizualizacje',
            'wpcf-zdjecia' => 'field_pokoje_zdjecia',
        ],
        // Mapowanie pól dla inspiracje
        'inspiracje' => [
            'wpcf-header' => 'field_inspiracje_header',
            'wpcf-header-picture' => 'field_inspiracje_header_picture',
            'wpcf-header-text' => 'field_inspiracje_header_text',
            'wpcf-subheader-text' => 'field_inspiracje_subheader_text',
            'wpcf-dodatkowy-opis-indodatkowy-opis-inspiracjaspiracja' => 'field_inspiracje_dodatkowy_opis',
        ]
    ];

    private $stats = [
        'pokoje_processed' => 0,
        'pokoje_fields_migrated' => 0,
        'inspiracje_processed' => 0,
        'inspiracje_fields_migrated' => 0,
        'relationships_migrated' => 0,
        'errors' => []
    ];

    public function run() {
        echo "=== Rozpoczynanie migracji Toolset → ACF ===\n\n";

        // Migracja pokoi
        $this->migrate_pokoje();

        // Migracja inspiracji
        $this->migrate_inspiracje();

        // Migracja relacji
        $this->migrate_relationships();

        // Podsumowanie
        $this->print_summary();
    }

    private function migrate_pokoje() {
        echo "Migracja pokoi dla dzieci...\n";

        $posts = get_posts([
            'post_type' => 'pokoje-dla-dzieci',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ]);

        foreach ($posts as $post) {
            echo "  - Migracja pokoju ID {$post->ID}: {$post->post_title}\n";

            foreach ($this->field_mapping['pokoje'] as $toolset_field => $acf_field) {
                $this->migrate_field($post->ID, $toolset_field, $acf_field, 'pokoje');
            }

            $this->stats['pokoje_processed']++;
        }

        echo "  ✓ Zmigrowano {$this->stats['pokoje_processed']} pokoi\n\n";
    }

    private function migrate_inspiracje() {
        echo "Migracja inspiracji...\n";

        $posts = get_posts([
            'post_type' => 'inspiracja',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ]);

        foreach ($posts as $post) {
            echo "  - Migracja inspiracji ID {$post->ID}: {$post->post_title}\n";

            foreach ($this->field_mapping['inspiracje'] as $toolset_field => $acf_field) {
                $this->migrate_field($post->ID, $toolset_field, $acf_field, 'inspiracje');
            }

            $this->stats['inspiracje_processed']++;
        }

        echo "  ✓ Zmigrowano {$this->stats['inspiracje_processed']} inspiracji\n\n";
    }

    private function migrate_field($post_id, $toolset_field, $acf_field, $post_type) {
        // Sprawdź czy to pole repetitive (galerie)
        if (in_array($toolset_field, ['wpcf-wizualizacje', 'wpcf-zdjecia'])) {
            $this->migrate_gallery_field($post_id, $toolset_field, $acf_field);
            return;
        }

        // Sprawdź czy to pole checkbox
        if ($toolset_field === 'wpcf-na-zamowienie') {
            $value = get_post_meta($post_id, $toolset_field, true);
            $acf_value = !empty($value) ? 1 : 0;
            update_field($acf_field, $acf_value, $post_id);
            $this->stats[$post_type . '_fields_migrated']++;
            return;
        }

        // Sprawdź czy to pole image
        if (strpos($toolset_field, 'header-picture') !== false) {
            $this->migrate_image_field($post_id, $toolset_field, $acf_field);
            return;
        }

        // Standardowe pole tekstowe/WYSIWYG
        $value = get_post_meta($post_id, $toolset_field, true);
        if (!empty($value)) {
            update_field($acf_field, $value, $post_id);
            $this->stats[$post_type . '_fields_migrated']++;
        }
    }

    private function migrate_gallery_field($post_id, $toolset_field, $acf_field) {
        // Toolset przechowuje repetitive images jako multiple meta values
        $images = get_post_meta($post_id, $toolset_field, false);

        if (empty($images)) {
            return;
        }

        $gallery_ids = [];

        foreach ($images as $image_url) {
            if (empty($image_url)) continue;

            // Znajdź ID załącznika na podstawie URL
            $attachment_id = $this->get_attachment_id_from_url($image_url);

            if ($attachment_id) {
                $gallery_ids[] = $attachment_id;
            } else {
                // Jeśli nie znaleziono, spróbuj zaimportować
                $attachment_id = $this->import_image_from_url($image_url, $post_id);
                if ($attachment_id) {
                    $gallery_ids[] = $attachment_id;
                }
            }
        }

        if (!empty($gallery_ids)) {
            update_field($acf_field, $gallery_ids, $post_id);
            $this->stats['pokoje_fields_migrated']++;
            echo "    → Zmigrowano galerię {$toolset_field}: " . count($gallery_ids) . " zdjęć\n";
        }
    }

    private function migrate_image_field($post_id, $toolset_field, $acf_field) {
        $image_url = get_post_meta($post_id, $toolset_field, true);

        if (empty($image_url)) {
            return;
        }

        // Znajdź ID załącznika
        $attachment_id = $this->get_attachment_id_from_url($image_url);

        if (!$attachment_id) {
            $attachment_id = $this->import_image_from_url($image_url, $post_id);
        }

        if ($attachment_id) {
            update_field($acf_field, $attachment_id, $post_id);
            $this->stats['pokoje_fields_migrated']++;
        }
    }

    private function get_attachment_id_from_url($url) {
        global $wpdb;

        // Usuń domenę i protokół
        $url = str_replace(home_url('/'), '', $url);

        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE guid LIKE %s",
            '%' . $url
        ));

        return $attachment_id ? intval($attachment_id) : 0;
    }

    private function import_image_from_url($url, $post_id) {
        // Sprawdź czy URL jest lokalny
        if (strpos($url, home_url()) !== 0) {
            return 0;
        }

        // Pobierz ścieżkę do pliku
        $upload_dir = wp_upload_dir();
        $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $url);

        if (!file_exists($file_path)) {
            $this->stats['errors'][] = "Nie znaleziono pliku: {$file_path}";
            return 0;
        }

        // Utwórz attachment
        $filename = basename($file_path);
        $filetype = wp_check_filetype($filename, null);

        $attachment = [
            'post_mime_type' => $filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        ];

        $attach_id = wp_insert_attachment($attachment, $file_path, $post_id);

        if (!is_wp_error($attach_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
            wp_update_attachment_metadata($attach_id, $attach_data);
            return $attach_id;
        }

        return 0;
    }

    private function migrate_relationships() {
        echo "Migracja relacji między pokojami a inspiracjami...\n";

        global $wpdb;

        // Pobierz relacje z tabeli Toolset
        $relationships = $wpdb->get_results("
            SELECT * FROM {$wpdb->prefix}toolset_associations
            WHERE relationship_id IN (
                SELECT id FROM {$wpdb->prefix}toolset_relationships
                WHERE slug = 'inspiracja-pokoj'
            )
        ");

        if (empty($relationships)) {
            echo "  ! Nie znaleziono relacji Toolset\n";
            return;
        }

        foreach ($relationships as $rel) {
            // parent_id = inspiracja, child_id = pokój
            $inspiracja_id = $rel->parent_id;
            $pokoj_id = $rel->child_id;

            // Pobierz obecne relacje ACF
            $current_pokoje = get_field('field_inspiracje_powiazane_pokoje', $inspiracja_id);
            if (!is_array($current_pokoje)) {
                $current_pokoje = [];
            }

            // Dodaj pokój do inspiracji
            $current_pokoje[] = $pokoj_id;
            update_field('field_inspiracje_powiazane_pokoje', array_unique($current_pokoje), $inspiracja_id);

            // Dodaj inspirację do pokoju (relacja dwukierunkowa)
            $current_inspiracje = get_field('field_pokoje_powiazane_inspiracje', $pokoj_id);
            if (!is_array($current_inspiracje)) {
                $current_inspiracje = [];
            }
            $current_inspiracje[] = $inspiracja_id;
            update_field('field_pokoje_powiazane_inspiracje', array_unique($current_inspiracje), $pokoj_id);

            $this->stats['relationships_migrated']++;
        }

        echo "  ✓ Zmigrowano {$this->stats['relationships_migrated']} relacji\n\n";
    }

    private function print_summary() {
        echo "\n=== PODSUMOWANIE MIGRACJI ===\n";
        echo "Pokoje zmigrowane: {$this->stats['pokoje_processed']}\n";
        echo "Pola pokoi zmigrowane: {$this->stats['pokoje_fields_migrated']}\n";
        echo "Inspiracje zmigrowane: {$this->stats['inspiracje_processed']}\n";
        echo "Pola inspiracji zmigrowane: {$this->stats['inspiracje_fields_migrated']}\n";
        echo "Relacje zmigrowane: {$this->stats['relationships_migrated']}\n";

        if (!empty($this->stats['errors'])) {
            echo "\nBŁĘDY:\n";
            foreach ($this->stats['errors'] as $error) {
                echo "  - {$error}\n";
            }
        }

        echo "\n✓ Migracja zakończona!\n";
        echo "\nZALECENIA:\n";
        echo "1. Sprawdź kilka przykładowych pokoi i inspiracji\n";
        echo "2. Jeśli wszystko OK, możesz dezaktywować Toolset\n";
        echo "3. Odśwież permalinki (Ustawienia → Bezpośrednie odnośniki)\n";
    }
}

// Uruchom migrację
$migration = new Toolset_To_ACF_Migration();
$migration->run();