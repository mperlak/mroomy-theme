<?php
/**
 * Test page for Room Category Tag Component
 * Template Name: Test Room Category Tag
 */

get_header();

// Załaduj komponent
mroomy_load_room_component( 'room-category-tag' );

?>

<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Test komponentu Room Category Tag</h1>

    <!-- Test pojedynczych tagów -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Pojedyncze tagi</h2>

        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium mb-2">Tag dla chłopca</h3>
                <?php mroomy_room_category_tag( array( 'category' => 'boy' ) ); ?>
            </div>

            <div>
                <h3 class="text-lg font-medium mb-2">Tag dla dziewczynki</h3>
                <?php mroomy_room_category_tag( array( 'category' => 'girl' ) ); ?>
            </div>

            <div>
                <h3 class="text-lg font-medium mb-2">Tag dla rodzeństwa</h3>
                <?php mroomy_room_category_tag( array( 'category' => 'siblings' ) ); ?>
            </div>
        </div>
    </section>

    <!-- Test wielu tagów razem -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Wiele tagów jednocześnie</h2>

        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium mb-2">Wszystkie tagi razem (manualne)</h3>
                <div class="mroomy-room-category-tags">
                    <?php
                    mroomy_room_category_tag( array( 'category' => 'boy' ) );
                    mroomy_room_category_tag( array( 'category' => 'girl' ) );
                    mroomy_room_category_tag( array( 'category' => 'siblings' ) );
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Test z niestandardowymi etykietami -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Niestandardowe etykiety</h2>

        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium mb-2">Tagi z własnymi tekstami</h3>
                <div class="mroomy-room-category-tags">
                    <?php
                    mroomy_room_category_tag( array(
                        'category' => 'boy',
                        'label' => 'Chłopcy 6-10 lat'
                    ) );
                    mroomy_room_category_tag( array(
                        'category' => 'girl',
                        'label' => 'Dziewczynki 3-5 lat'
                    ) );
                    mroomy_room_category_tag( array(
                        'category' => 'siblings',
                        'label' => 'Bliźniaki'
                    ) );
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Test wariantów rozmiarów -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Warianty rozmiarów</h2>

        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium mb-2">Small</h3>
                <div class="mroomy-room-category-tags">
                    <?php
                    mroomy_room_category_tag( array(
                        'category' => 'boy',
                        'class' => 'mroomy-room-category-tag--small'
                    ) );
                    mroomy_room_category_tag( array(
                        'category' => 'girl',
                        'class' => 'mroomy-room-category-tag--small'
                    ) );
                    ?>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium mb-2">Medium (domyślny)</h3>
                <div class="mroomy-room-category-tags">
                    <?php
                    mroomy_room_category_tag( array( 'category' => 'boy' ) );
                    mroomy_room_category_tag( array( 'category' => 'girl' ) );
                    ?>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium mb-2">Large</h3>
                <div class="mroomy-room-category-tags">
                    <?php
                    mroomy_room_category_tag( array(
                        'category' => 'boy',
                        'class' => 'mroomy-room-category-tag--large'
                    ) );
                    mroomy_room_category_tag( array(
                        'category' => 'girl',
                        'class' => 'mroomy-room-category-tag--large'
                    ) );
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Test z prawdziwymi postami -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Tagi z prawdziwych postów</h2>

        <?php
        // Pobierz przykładowe pokoje z różnymi kategoriami
        $args = array(
            'post_type'      => 'pokoje-dla-dzieci',
            'posts_per_page' => 5,
            'orderby'        => 'rand'
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) : ?>
            <div class="space-y-4">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="text-lg font-medium mb-2"><?php the_title(); ?></h3>
                        <p class="text-sm text-gray-600 mb-2">Post ID: <?php echo get_the_ID(); ?></p>
                        <?php
                        // Użyj funkcji do wyświetlenia tagów dla tego posta
                        mroomy_room_category_tags( array(
                            'post_id' => get_the_ID()
                        ) );
                        ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="text-red-600">Brak postów typu "pokoje-dla-dzieci" do testowania.</p>
        <?php endif;
        wp_reset_postdata();
        ?>
    </section>

    <!-- Test z bezpośrednim użyciem sługu taksonomii -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Bezpośrednie użycie sługu taksonomii</h2>

        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium mb-2">Użycie pełnych nazw sługu</h3>
                <div class="mroomy-room-category-tags">
                    <?php
                    mroomy_room_category_tag( array( 'category' => 'pokoje-dla-chlopcow' ) );
                    mroomy_room_category_tag( array( 'category' => 'pokoje-dla-dziewczynek' ) );
                    mroomy_room_category_tag( array( 'category' => 'pokoje-dla-rodzenstwa' ) );
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Informacje o CSS -->
    <section class="border p-6 rounded">
        <h2 class="text-2xl font-semibold mb-4">Sprawdzenie CSS</h2>
        <div class="space-y-2">
            <p>✅ Style dla kategorii chłopców (niebieski motyw)</p>
            <p>✅ Style dla kategorii dziewczynek (różowy motyw)</p>
            <p>✅ Style dla kategorii rodzeństwa (fioletowy motyw)</p>
            <p>✅ Efekty hover</p>
            <p>✅ Animacje pojawiania się</p>
            <p>✅ Obsługa wielu tagów</p>
            <p>✅ Warianty rozmiarów (small, medium, large)</p>
            <p>✅ Style dostępności (focus-visible)</p>
            <p>✅ Wsparcie dla trybu wysokiego kontrastu</p>
            <p>✅ Style druku</p>
        </div>
    </section>

</div>

<?php
get_footer();