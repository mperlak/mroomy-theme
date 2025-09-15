<?php
/**
 * Strona admina do uruchomienia migracji
 * Dodaj to tymczasowo do functions.php:
 * require_once get_template_directory() . '/migration/run-migration-admin.php';
 */

add_action('admin_menu', 'toolset_acf_migration_menu');

function toolset_acf_migration_menu() {
    add_management_page(
        'Migracja Toolset → ACF',
        'Migracja Toolset → ACF',
        'manage_options',
        'toolset-acf-migration',
        'toolset_acf_migration_page'
    );
}

function toolset_acf_migration_page() {
    ?>
    <div class="wrap">
        <h1>Migracja danych z Toolset do ACF</h1>

        <?php if (isset($_GET['run']) && $_GET['run'] === 'true'): ?>
            <div class="notice notice-info">
                <p>Uruchamianie migracji...</p>
            </div>

            <pre style="background: #f1f1f1; padding: 20px; overflow: auto; max-height: 500px;">
<?php
            // Włącz buforowanie
            ob_start();

            // Uruchom migrację
            require_once get_template_directory() . '/migration/05-migrate-data.php';

            // Wyświetl wynik
            $output = ob_get_clean();
            echo esc_html($output);
?>
            </pre>

            <p><a href="<?php echo admin_url('tools.php?page=toolset-acf-migration'); ?>" class="button">Powrót</a></p>

        <?php else: ?>

            <div class="card" style="max-width: 600px; padding: 20px;">
                <h2>Przed rozpoczęciem:</h2>
                <ul>
                    <li>✓ Backup bazy danych wykonany</li>
                    <li>✓ ACF Post Types zaimportowane</li>
                    <li>✓ ACF Field Groups zaimportowane</li>
                    <li>✓ Permalinki odświeżone</li>
                </ul>

                <h2>Co zostanie zmigrowane:</h2>
                <ul>
                    <li>Wszystkie pola z pokoi dla dzieci (17 pól)</li>
                    <li>Wszystkie pola z inspiracji (5 pól)</li>
                    <li>Galerie zdjęć (konwersja na ACF Gallery)</li>
                    <li>Relacje między pokojami a inspiracjami</li>
                </ul>

                <p style="color: red;"><strong>UWAGA:</strong> Uruchom migrację tylko RAZ!</p>

                <p>
                    <a href="<?php echo admin_url('tools.php?page=toolset-acf-migration&run=true'); ?>"
                       class="button button-primary"
                       onclick="return confirm('Czy na pewno chcesz uruchomić migrację? Upewnij się, że masz backup!');">
                        Uruchom migrację
                    </a>
                </p>
            </div>

        <?php endif; ?>
    </div>
    <?php
}
?>