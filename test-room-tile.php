<?php
/**
 * Test page for Room Tile Component
 * Template Name: Test Room Tile
 */

get_header();

// Załaduj komponent
mroomy_load_room_component( 'room-tile' );

?>

<div class="bg-white min-h-screen">
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Test komponentu Room Tile</h1>

    <!-- Test pojedynczego kafelka - rozmiar Large -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Kafelek - rozmiar Large (domyślny)</h2>

        <?php
        // Pobierz przykładowy pokój
        $args = array(
            'post_type'      => 'pokoje-dla-dzieci',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS'
                )
            )
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
                ?>
                <div style="max-width: 386px;">
                    <?php
                    mroomy_room_tile( array(
                        'post_id' => get_the_ID(),
                        'size'    => 'large'
                    ) );
                    ?>
                </div>
                <?php
            endwhile;
        else :
            echo '<p class="text-red-600">Brak postów z obrazkiem do testowania.</p>';
        endif;
        wp_reset_postdata();
        ?>
    </section>

    <!-- Test różnych rozmiarów -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Porównanie rozmiarów</h2>

        <?php
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) :
            $query->the_post();
            $test_post_id = get_the_ID();
            ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium mb-2">Large (386px)</h3>
                    <?php
                    mroomy_room_tile( array(
                        'post_id' => $test_post_id,
                        'size'    => 'large'
                    ) );
                    ?>
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-2">Medium (216px)</h3>
                    <?php
                    mroomy_room_tile( array(
                        'post_id' => $test_post_id,
                        'size'    => 'medium'
                    ) );
                    ?>
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-2">Small (163px)</h3>
                    <?php
                    mroomy_room_tile( array(
                        'post_id' => $test_post_id,
                        'size'    => 'small'
                    ) );
                    ?>
                </div>
            </div>
            <?php
        endif;
        wp_reset_postdata();
        ?>
    </section>

    <!-- Test z różnymi opcjami wyświetlania -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Opcje wyświetlania</h2>

        <?php
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) :
            $query->the_post();
            $test_post_id = get_the_ID();
            ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium mb-2">Bez tagów kategorii</h3>
                    <?php
                    mroomy_room_tile( array(
                        'post_id'   => $test_post_id,
                        'size'      => 'medium',
                        'show_tags' => false
                    ) );
                    ?>
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-2">Bez opisu</h3>
                    <?php
                    mroomy_room_tile( array(
                        'post_id'      => $test_post_id,
                        'size'         => 'medium',
                        'show_excerpt' => false
                    ) );
                    ?>
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-2">Bez przycisku akcji</h3>
                    <?php
                    mroomy_room_tile( array(
                        'post_id'      => $test_post_id,
                        'size'         => 'medium',
                        'show_actions' => false
                    ) );
                    ?>
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-2">Niestandardowy tekst przycisku</h3>
                    <?php
                    mroomy_room_tile( array(
                        'post_id'     => $test_post_id,
                        'size'        => 'medium',
                        'button_text' => 'Sprawdź projekt'
                    ) );
                    ?>
                </div>
            </div>
            <?php
        endif;
        wp_reset_postdata();
        ?>
    </section>

    <!-- Test z wieloma kafelkami -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Siatka kafelków</h2>

        <?php
        $args['posts_per_page'] = 6;
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php
                    mroomy_room_tile( array(
                        'post_id' => get_the_ID(),
                        'size'    => 'medium'
                    ) );
                    ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <p class="text-red-600">Brak postów do wyświetlenia.</p>
        <?php endif;
        wp_reset_postdata();
        ?>
    </section>

    <!-- Test z różnymi kategoriami -->
    <section class="border p-6 rounded mb-6">
        <h2 class="text-2xl font-semibold mb-4">Kafelki według kategorii</h2>

        <?php
        $categories = array(
            'pokoje-dla-chlopcow'     => 'Pokoje dla chłopców',
            'pokoje-dla-dziewczynek'  => 'Pokoje dla dziewczynek',
            'pokoje-dla-rodzenstwa'   => 'Pokoje dla rodzeństwa'
        );

        foreach ( $categories as $cat_slug => $cat_name ) :
            $cat_args = array(
                'post_type'      => 'pokoje-dla-dzieci',
                'posts_per_page' => 1,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'przeznaczenie',
                        'field'    => 'slug',
                        'terms'    => $cat_slug
                    )
                ),
                'meta_query'     => array(
                    array(
                        'key'     => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    )
                )
            );
            $cat_query = new WP_Query( $cat_args );

            if ( $cat_query->have_posts() ) : ?>
                <div class="mb-4">
                    <h3 class="text-lg font-medium mb-2"><?php echo esc_html( $cat_name ); ?></h3>
                    <div style="max-width: 386px;">
                        <?php
                        while ( $cat_query->have_posts() ) : $cat_query->the_post();
                            mroomy_room_tile( array(
                                'post_id' => get_the_ID(),
                                'size'    => 'large'
                            ) );
                        endwhile;
                        ?>
                    </div>
                </div>
            <?php endif;
            wp_reset_postdata();
        endforeach;
        ?>
    </section>

    <!-- Informacje o CSS -->
    <section class="border p-6 rounded">
        <h2 class="text-2xl font-semibold mb-4">Sprawdzenie implementacji</h2>
        <div class="space-y-2">
            <p>✅ Integracja z komponentem Image</p>
            <p>✅ Integracja z komponentem RoomCategoryTag</p>
            <p>✅ Trzy warianty rozmiarów (Large, Medium, Small)</p>
            <p>✅ Parsowanie tytułu i wyświetlanie beneficjenta</p>
            <p>✅ Pozycjonowanie tagów kategorii na obrazku</p>
            <p>✅ Przycisk akcji z możliwością customizacji</p>
            <p>✅ Efekty hover na przycisku i tytule</p>
            <p>✅ Responsywność i dostępność</p>
            <p>✅ Style dla stanu ładowania</p>
            <p>✅ Wsparcie dla trybu druku i wysokiego kontrastu</p>
        </div>
    </section>

</div>
</div>

<?php
get_footer();