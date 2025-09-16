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

        <?php if (isset($_GET['run'])): ?>
            <?php
            $step = $_GET['run'];
            $title = '';
            $file = '';

            switch($step) {
                case 'fields':
                    $title = 'Migracja pól podstawowych';
                    $file = '/migration/05-migrate-data.php';
                    break;
                case 'products':
                    $title = 'Migracja relacji pokoje-produkty';
                    $file = '/migration/10-migrate-products-relationships.php';
                    break;
            }
            ?>

            <div class="notice notice-info">
                <p>Uruchamianie: <?php echo esc_html($title); ?>...</p>
            </div>

            <pre style="background: #f1f1f1; padding: 20px; overflow: auto; max-height: 500px;">
<?php
            // Włącz buforowanie
            ob_start();

            // Uruchom odpowiednią migrację
            if ($file && file_exists(get_template_directory() . $file)) {
                require_once get_template_directory() . $file;
            } else {
                echo "Błąd: Nie znaleziono pliku migracji.";
            }

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
                    <li>Relacje między pokojami a produktami WooCommerce</li>
                </ul>

                <p style="color: red;"><strong>UWAGA:</strong> Uruchom każdą migrację tylko RAZ!</p>

                <h2>Kroki migracji:</h2>
                <ol>
                    <li>
                        <strong>Krok 1: Migracja pól podstawowych</strong><br>
                        <small>Migruje wszystkie pola tekstowe, obrazy i galerie</small><br>
                        <a href="<?php echo admin_url('tools.php?page=toolset-acf-migration&run=fields'); ?>"
                           class="button button-primary"
                           onclick="return confirm('Czy na pewno chcesz uruchomić migrację pól? Upewnij się, że masz backup!');">
                            Uruchom migrację pól
                        </a>
                    </li>
                    <li style="margin-top: 20px;">
                        <strong>Krok 2: Migracja relacji pokoje-produkty</strong><br>
                        <small>Przypisuje produkty WooCommerce do pokoi</small><br>
                        <a href="<?php echo admin_url('tools.php?page=toolset-acf-migration&run=products'); ?>"
                           class="button button-primary"
                           onclick="return confirm('Czy na pewno chcesz uruchomić migrację produktów? Upewnij się, że masz backup!');">
                            Uruchom migrację produktów
                        </a>
                    </li>
                </ol>
            </div>

        <?php endif; ?>
    </div>
    <?php
}
?>