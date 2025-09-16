<?php
/**
 * Migracja relacji pokoje-produkty z Toolset do ACF
 *
 * Uruchom przez WP-CLI:
 * ddev wp eval-file wp-content/themes/mroomy_s/migration/10-migrate-products-relationships.php
 */

// Sprawdź czy Toolset jest dostępny
if (!function_exists('toolset_get_related_posts')) {
    die("Błąd: Toolset nie jest aktywny. Włącz Toolset przed migracją.\n");
}

// Sprawdź czy ACF jest dostępny
if (!function_exists('update_field')) {
    die("Błąd: ACF nie jest aktywny.\n");
}

echo "Rozpoczynam migrację relacji pokoje-produkty z Toolset do ACF...\n\n";

// Pobierz wszystkie pokoje
$pokoje = get_posts(array(
    'post_type' => 'pokoje-dla-dzieci',
    'posts_per_page' => -1,
    'post_status' => 'any'
));

$total = count($pokoje);
$migrated = 0;
$skipped = 0;
$errors = 0;

echo "Znaleziono {$total} pokoi do przetworzenia.\n\n";

foreach ($pokoje as $pokoj) {
    echo "Przetwarzam pokój ID: {$pokoj->ID} - {$pokoj->post_title}\n";

    try {
        // Pobierz produkty z Toolset
        // UWAGA: Relacja jest odwrotnie - pokoje są children, produkty są parent
        $products = toolset_get_related_posts(
            $pokoj->ID,
            'produkty-z-projektu',
            array(
                'query_by_role' => 'child',  // zmienione z 'parent' na 'child'
                'role_to_return' => 'other',
                'limit' => 999 // Pobierz wszystkie
            )
        );

        if (empty($products)) {
            echo "  → Brak produktów do migracji\n";
            $skipped++;
            continue;
        }

        $product_count = count($products);
        echo "  → Znaleziono {$product_count} produktów\n";

        // Sprawdź czy już nie ma przypisanych produktów w ACF
        $existing_acf = get_field('produkty_z_projektu', $pokoj->ID);
        if (!empty($existing_acf)) {
            echo "  → UWAGA: Pokój ma już produkty w ACF (" . count($existing_acf) . "). Pomijam.\n";
            $skipped++;
            continue;
        }

        // Zapisz produkty w ACF
        $result = update_field('produkty_z_projektu', $products, $pokoj->ID);

        if ($result) {
            echo "  → ✓ Zmigrowano {$product_count} produktów\n";
            $migrated++;

            // Weryfikacja
            $verify = get_field('produkty_z_projektu', $pokoj->ID);
            if (count($verify) != $product_count) {
                echo "  → OSTRZEŻENIE: Weryfikacja nieudana. Oczekiwano {$product_count}, zapisano " . count($verify) . "\n";
            }
        } else {
            echo "  → ✗ Błąd podczas zapisywania\n";
            $errors++;
        }

    } catch (Exception $e) {
        echo "  → ✗ Błąd: " . $e->getMessage() . "\n";
        $errors++;
    }

    echo "\n";
}

echo "========================================\n";
echo "PODSUMOWANIE MIGRACJI:\n";
echo "========================================\n";
echo "Wszystkich pokoi: {$total}\n";
echo "Zmigrowano: {$migrated}\n";
echo "Pominięto: {$skipped}\n";
echo "Błędy: {$errors}\n";
echo "========================================\n";

// Sprawdź kilka przykładów
echo "\nPRZYKŁADOWE WERYFIKACJE:\n";
$examples = array_slice($pokoje, 0, 3);
foreach ($examples as $pokoj) {
    $acf_products = get_field('produkty_z_projektu', $pokoj->ID);
    $count = is_array($acf_products) ? count($acf_products) : 0;
    echo "Pokój {$pokoj->ID}: {$count} produktów w ACF\n";
}

echo "\n✓ Migracja zakończona!\n";
echo "\nPamiętaj aby:\n";
echo "1. Sprawdzić wybrane pokoje w panelu admina\n";
echo "2. Po weryfikacji wyłączyć Toolset Blocks\n";
echo "3. Usunąć tabele Toolset jeśli nie są już potrzebne\n";