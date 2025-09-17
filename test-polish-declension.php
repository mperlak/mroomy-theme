<?php
/**
 * Test page for Polish names declension
 * Template Name: Test Polish Declension
 */

get_header();

// Include declension helper
require_once get_template_directory() . '/inc/polish-names-declension.php';

?>

<div class="bg-white min-h-screen">
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Test odmiany polskich imion (dopełniacz)</h1>

    <!-- Test pojedynczych imion -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Pojedyncze imiona</h2>

        <?php
        $test_single_names = array(
            'Oliwier' => 'Oliwiera',
            'Henio' => 'Henia',
            'Zosia' => 'Zosi',
            'Julia' => 'Julii',
            'Maja' => 'Mai',
            'Antoni' => 'Antoniego',
            'Jaś' => 'Jasia',
            'Hania' => 'Hani',
            'Piotr' => 'Piotra',
            'Magdalena' => 'Magdaleny',
            'Kasia' => 'Kasi',
            'Tomek' => 'Tomka',
            'Ania' => 'Ani',
            'Bartek' => 'Bartka',
            'Franek' => 'Franka',
            'Zuzia' => 'Zuzi',
            'Mateusz' => 'Mateusza',
            'Aleksandra' => 'Aleksandry',
            'Kamil' => 'Kamila',
            'Natalia' => 'Natalii',
        );
        ?>

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-2">Imię (mianownik)</th>
                    <th class="text-left p-2">Dla kogo? (dopełniacz)</th>
                    <th class="text-left p-2">Oczekiwany</th>
                    <th class="text-left p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $test_single_names as $name => $expected ) :
                    $actual = mroomy_get_genitive_form( $name );
                    $is_correct = ( $actual === $expected );
                ?>
                <tr class="border-b">
                    <td class="p-2"><?php echo esc_html( $name ); ?></td>
                    <td class="p-2 font-semibold"><?php echo esc_html( $actual ); ?></td>
                    <td class="p-2 text-gray-600"><?php echo esc_html( $expected ); ?></td>
                    <td class="p-2">
                        <?php if ( $is_correct ) : ?>
                            <span class="text-green-600">✅</span>
                        <?php else : ?>
                            <span class="text-red-600">❌</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Test wielu imion -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Wiele imion (rodzeństwo)</h2>

        <?php
        $test_multiple_names = array(
            'Oliwier i Maja' => 'Oliwiera i Mai',
            'Henio i Zosia' => 'Henia i Zosi',
            'Piotr, Paweł i Tomek' => 'Piotra, Pawła i Tomka',
            'Ania i Kasia' => 'Ani i Kasi',
            'Julia, Natalia i Aleksandra' => 'Julii, Natalii i Aleksandry',
            'Franek i Bartek' => 'Franka i Bartka',
            'rodzeństwo' => 'rodzeństwo',
            'bliźnięta' => 'bliźnięta',
        );
        ?>

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-2">Imiona (mianownik)</th>
                    <th class="text-left p-2">Dla kogo? (dopełniacz)</th>
                    <th class="text-left p-2">Oczekiwany</th>
                    <th class="text-left p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $test_multiple_names as $names => $expected ) :
                    $actual = mroomy_decline_multiple_names( $names );
                    $is_correct = ( $actual === $expected );
                ?>
                <tr class="border-b">
                    <td class="p-2"><?php echo esc_html( $names ); ?></td>
                    <td class="p-2 font-semibold"><?php echo esc_html( $actual ); ?></td>
                    <td class="p-2 text-gray-600"><?php echo esc_html( $expected ); ?></td>
                    <td class="p-2">
                        <?php if ( $is_correct ) : ?>
                            <span class="text-green-600">✅</span>
                        <?php else : ?>
                            <span class="text-red-600">❌</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Test pełnych tytułów -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Pełne tytuły pokoi</h2>

        <?php
        $test_titles = array(
            'Projekt biało-żółtego pokoju dla chłopca, Oliwier (8 lat)' => array(
                'main' => 'Projekt biało-żółtego pokoju dla chłopca',
                'beneficiary' => 'Projekt dla Oliwiera (8 lat)'
            ),
            'Projekt pokoju z łóżkiem domkiem, Henio (3 lata)' => array(
                'main' => 'Projekt pokoju z łóżkiem domkiem',
                'beneficiary' => 'Projekt dla Henia (3 lata)'
            ),
            'Projekt różowo-białego pokoju dla dziewczynki, Zosia (5 lat)' => array(
                'main' => 'Projekt różowo-białego pokoju dla dziewczynki',
                'beneficiary' => 'Projekt dla Zosi (5 lat)'
            ),
            'Projekt pokoju dla rodzeństwa, Ania i Tomek (6 i 4 lata)' => array(
                'main' => 'Projekt pokoju dla rodzeństwa',
                'beneficiary' => 'Projekt dla Ani i Tomka (6 i 4 lata)'
            ),
            'Projekt morskiego pokoju, Piotr i Paweł (bliźnięta, 7 lat)' => array(
                'main' => 'Projekt morskiego pokoju',
                'beneficiary' => 'Projekt dla Piotra i Pawła (bliźnięta, 7 lat)'
            ),
        );
        ?>

        <div class="space-y-4">
            <?php foreach ( $test_titles as $title => $expected ) :
                $parsed = mroomy_parse_room_title( $title );
            ?>
            <div class="border p-4 rounded bg-gray-50">
                <div class="mb-2">
                    <strong>Oryginalny tytuł:</strong><br>
                    <span class="text-gray-700"><?php echo esc_html( $title ); ?></span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <strong>Tytuł główny:</strong><br>
                        <span class="text-blue-600"><?php echo esc_html( $parsed['main'] ); ?></span><br>
                        <span class="text-xs text-gray-500">Oczekiwany: <?php echo esc_html( $expected['main'] ); ?></span>
                        <?php if ( $parsed['main'] === $expected['main'] ) : ?>
                            <span class="text-green-600 ml-2">✅</span>
                        <?php else : ?>
                            <span class="text-red-600 ml-2">❌</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong>Beneficjent (dla kogo):</strong><br>
                        <span class="text-purple-600"><?php echo esc_html( $parsed['beneficiary'] ); ?></span><br>
                        <span class="text-xs text-gray-500">Oczekiwany: <?php echo esc_html( $expected['beneficiary'] ); ?></span>
                        <?php if ( $parsed['beneficiary'] === $expected['beneficiary'] ) : ?>
                            <span class="text-green-600 ml-2">✅</span>
                        <?php else : ?>
                            <span class="text-red-600 ml-2">❌</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Test komponentu Room Tile z odmianą -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Przykład w komponencie Room Tile</h2>

        <?php
        // Load room tile component
        mroomy_load_room_component( 'room-tile' );

        // Get a sample room post
        $sample_post = get_posts( array(
            'post_type' => 'pokoje-dla-dzieci',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                )
            )
        ) );

        if ( ! empty( $sample_post ) ) :
            $post = $sample_post[0];
            ?>
            <div class="mb-4">
                <strong>Oryginalny tytuł posta:</strong> <?php echo esc_html( $post->post_title ); ?>
            </div>
            <div style="max-width: 386px;">
                <?php
                mroomy_room_tile( array(
                    'post_id' => $post->ID,
                    'size' => 'large'
                ) );
                ?>
            </div>
        <?php else : ?>
            <p class="text-red-600">Brak postów typu 'pokoje-dla-dzieci' do testowania.</p>
        <?php endif; ?>
    </section>

    <!-- Interaktywny test -->
    <section class="border p-6 rounded">
        <h2 class="text-2xl font-semibold mb-4">Interaktywny test</h2>
        <p class="mb-4">Wpisz imię, aby zobaczyć jego formę w dopełniaczu:</p>

        <div class="flex gap-4">
            <input type="text" id="test-name" placeholder="np. Oliwier" class="border rounded px-3 py-2 flex-1">
            <button onclick="testDeclension()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Testuj odmianę
            </button>
        </div>

        <div id="test-result" class="mt-4 p-4 bg-gray-100 rounded hidden">
            <strong>Wynik:</strong> <span id="result-text"></span>
        </div>

        <script>
        function testDeclension() {
            const input = document.getElementById('test-name');
            const resultDiv = document.getElementById('test-result');
            const resultText = document.getElementById('result-text');

            if (input.value.trim()) {
                // This would need AJAX to call PHP function
                // For now, just show the input
                resultDiv.classList.remove('hidden');
                resultText.textContent = 'Projekt dla [tutaj będzie odmienione imię] - wymaga implementacji AJAX';
            }
        }
        </script>
    </section>

</div>
</div>

<?php
get_footer();