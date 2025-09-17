<?php
/**
 * Test page for Rooms List Component
 * Template Name: Test Rooms List
 */

get_header();

// Załaduj komponent
mroomy_load_room_component( 'rooms-list' );

?>

<div class="bg-white min-h-screen overflow-x-hidden">
<div class="container mx-auto">
    <div class="p-8">
        <h1 class="text-3xl font-bold mb-6">Test komponentu Rooms List</h1>
    </div>

    <!-- Test domyślnej karuzeli -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Domyślna karuzela</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'posts_per_page' => 8
        ) );
        ?>
    </section>

    <!-- Test z różnymi rozmiarami kafelków -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Karuzela z kafelkami Medium</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'title'          => 'Pokoje średniego rozmiaru',
            'button_text'    => 'Zobacz więcej',
            'posts_per_page' => 6,
            'tile_size'      => 'medium'
        ) );
        ?>
    </section>

    <!-- Test z filtrowaniem po kategorii -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Tylko pokoje dla chłopców</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'title'          => 'Pokoje dla chłopców',
            'button_text'    => 'Wszystkie pokoje dla chłopców',
            'posts_per_page' => 4,
            'categories'     => array( 'pokoje-dla-chlopcow' ),
            'tile_size'      => 'large'
        ) );
        ?>
    </section>

    <!-- Test z filtrowaniem po kategorii - dziewczynki -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Tylko pokoje dla dziewczynek</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'title'          => 'Pokoje dla dziewczynek',
            'button_text'    => 'Wszystkie pokoje dla dziewczynek',
            'posts_per_page' => 4,
            'categories'     => array( 'pokoje-dla-dziewczynek' ),
            'tile_size'      => 'medium'
        ) );
        ?>
    </section>

    <!-- Test trybu siatki (bez karuzeli) -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Tryb siatki (bez karuzeli)</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'title'           => 'Wszystkie projekty',
            'button_text'     => 'Zobacz archiwum',
            'posts_per_page'  => 6,
            'enable_carousel' => false,
            'tile_size'       => 'medium'
        ) );
        ?>
    </section>

    <!-- Test bez nagłówka -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Karuzela bez nagłówka</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'posts_per_page' => 5,
            'show_header'    => false,
            'tile_size'      => 'small'
        ) );
        ?>
    </section>

    <!-- Test z określonymi postami -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Określone posty (jeśli istnieją)</h2>
        </div>
        <?php
        // Pobierz pierwsze 3 posty do testowania
        $test_posts = get_posts( array(
            'post_type'      => 'pokoje-dla-dzieci',
            'posts_per_page' => 3,
            'fields'         => 'ids'
        ) );

        if ( ! empty( $test_posts ) ) {
            mroomy_rooms_list( array(
                'title'       => 'Wybrane projekty',
                'button_text' => 'Zobacz wybrane',
                'post_ids'    => $test_posts,
                'tile_size'   => 'large'
            ) );
        } else {
            echo '<p class="p-8 text-red-600">Brak postów do wyświetlenia.</p>';
        }
        ?>
    </section>

    <!-- Test sortowania -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Sortowanie alfabetyczne</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'title'          => 'Projekty A-Z',
            'button_text'    => 'Zobacz wszystkie alfabetycznie',
            'posts_per_page' => 6,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'tile_size'      => 'medium'
        ) );
        ?>
    </section>

    <!-- Test z niestandardową klasą CSS -->
    <section class="mb-12">
        <div class="p-8">
            <h2 class="text-2xl font-semibold mb-4">Z niestandardową klasą CSS</h2>
        </div>
        <?php
        mroomy_rooms_list( array(
            'title'          => 'Sekcja z custom class',
            'posts_per_page' => 4,
            'tile_size'      => 'large',
            'class'          => 'custom-rooms-section bg-gray-50'
        ) );
        ?>
    </section>

    <!-- Informacje o implementacji -->
    <section class="border p-6 rounded m-8">
        <h2 class="text-2xl font-semibold mb-4">Sprawdzenie implementacji</h2>
        <div class="space-y-2">
            <p>✅ Karuzela z Swiper.js</p>
            <p>✅ Nawigacja strzałkami (tylko swipe)</p>
            <p>✅ Responsywne breakpointy (1/2/3/4 kolumny)</p>
            <p>✅ Nagłówek sekcji z tytułem i przyciskiem</p>
            <p>✅ Filtrowanie po kategoriach</p>
            <p>✅ Określone posty lub automatyczny wybór</p>
            <p>✅ Tryb siatki (bez karuzeli)</p>
            <p>✅ Różne rozmiary kafelków</p>
            <p>✅ Sortowanie i kolejność</p>
            <p>✅ Integracja z komponentem RoomTile</p>
            <p>✅ Unikalne ID dla wielu instancji</p>
            <p>✅ Style loading state</p>
        </div>
    </section>

</div>
</div>

<?php
get_footer();