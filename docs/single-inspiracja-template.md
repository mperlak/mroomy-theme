# Single Inspiracja Template - Dokumentacja

## Opis
Szablon `single-inspiracja.php` wyświetla pojedynczą inspirację wraz z powiązanymi pokojami dziecięcymi z tej samej kategorii.

## Struktura Plików

```
wp-content/themes/mroomy_s/
├── single-inspiracja.php                          # Główny szablon
├── template-parts/
│   ├── inspiration-hero.php                       # Hero section (obrazek, gradient, breadcrumbs, tytuł)
│   ├── inspiration-intro-text.php                 # Tekst wprowadzający
│   └── inspiration-rooms-grid.php                 # Grid 3×4 pokoi z paginacją
├── inc/
│   └── inspirations-template-functions.php        # Funkcje pomocnicze
├── components/
│   ├── filters/
│   │   └── rooms-filters/
│   │       └── rooms-filters.php                  # Reużywalny komponent filtrów (mockup)
│   └── inspirations/
│       └── related-styles/
│           └── related-styles.php                 # Placeholder sekcji stylów
└── assets/css/
    ├── inspiration-hero.css                       # Style hero section
    ├── inspiration-intro-text.css                 # Style tekstu intro
    └── inspiration-rooms-grid.css                 # Style gridu i paginacji
```

## Funkcje Pomocnicze

### `mroomy_get_inspiration_rooms_query( $inspiration_id, $args = array() )`

Pobiera pokoje (pokoje-dla-dzieci) z tej samej kategorii (kategoria-pokoi) co dana inspiracja.

**Parametry:**
- `$inspiration_id` (int) - ID posta inspiracji
- `$args` (array) - Opcjonalne argumenty:
  - `posts_per_page` (int) - Liczba postów na stronę. Default: 12
  - `paged` (int) - Numer strony. Default: z `get_query_var('paged')`
  - `orderby` (string) - Sortowanie: 'date' lub 'rand'. Default: z `INSPIRATION_ROOMS_SORT`
  - `order` (string) - Kolejność: 'ASC' lub 'DESC'. Default: 'DESC'
  - `exclude` (array) - Tablica ID postów do wykluczenia. Default: []

**Zwraca:** `WP_Query|null`

**Przykład:**
```php
$rooms = mroomy_get_inspiration_rooms_query( get_the_ID(), array(
    'posts_per_page' => 12,
    'paged' => 1
) );

if ( $rooms && $rooms->have_posts() ) {
    while ( $rooms->have_posts() ) {
        $rooms->the_post();
        // Display room
    }
    wp_reset_postdata();
}
```

### Session Tracking (dla losowego sortowania)

#### `mroomy_get_viewed_rooms_session( $inspiration_id )`
Pobiera wyświetlone pokoje z sesji dla konkretnej inspiracji.

#### `mroomy_add_viewed_rooms_session( $room_ids, $inspiration_id )`
Dodaje wyświetlone pokoje do sesji.

#### `mroomy_save_viewed_rooms( $query, $inspiration_id )`
Zapisuje wyświetlone pokoje po renderowaniu gridu.

#### `mroomy_clear_viewed_rooms_session( $inspiration_id )`
Czyści sesję wyświetlonych pokoi.

**Przykład:**
```php
// Po wyświetleniu pokoi:
mroomy_save_viewed_rooms( $rooms_query, get_the_ID() );

// Czyszczenie przy zmianie filtrów:
mroomy_clear_viewed_rooms_session( get_the_ID() );
```

## Komponenty Reużywalne

### Filtry Pokoi (Mockup)

```php
echo mroomy_rooms_filters( array(
    'context'      => 'inspiration',
    'category_id'  => 123,
    'action_url'   => '',
    'button_text'  => 'Filtruj'
) );
```

### Related Styles Placeholder

```php
echo mroomy_related_styles_placeholder( array(
    'inspiration_id' => get_the_ID(),
    'title'          => 'Poznaj kategorie w innych stylach'
) );
```

## Konfiguracja

### Sortowanie Pokoi

W pliku `inc/inspirations-template-functions.php`:

```php
define( 'INSPIRATION_ROOMS_SORT', 'random' ); // lub 'date'
```

- `'random'` - Losowe sortowanie z session tracking (bez duplikatów przy paginacji)
- `'date'` - Sortowanie po dacie publikacji (DESC)

## ACF Fields

Szablon wymaga następujących pól ACF dla post type `inspiracja`:

- `header_picture` (Image) - Obrazek hero section

## Template Parts

### Hero Section
- Wysokość: 320px (desktop), responsywna (mobile)
- Obrazek z ACF `header_picture`
- Gradient overlay: `from-black/30 to-transparent`
- Breadcrumbs: Strona główna > Inspiracje > [Tytuł]
- Tytuł: Nunito ExtraBold 52px

### Intro Text
- Padding: 106px L/R
- Max width: 1228px
- Font: Nunito SemiBold 20px
- Pobiera treść z `the_content()`

### Rooms Grid
- Grid: 3 kolumny (desktop), 2 kolumny (tablet), 1 kolumna (mobile)
- Gap: 32px
- 12 pokoi na stronę
- Notification bar: "Znaleziono X projektów"
- Paginacja: styled, wycentrowana

## Responsywność

### Desktop (1280px+)
- Hero: 320px, padding 106px L/R
- Grid: 3 kolumny, gap 32px

### Tablet (768px - 1279px)
- Hero: 240px, padding 24px L/R
- Grid: 2 kolumny, gap 24px

### Mobile (< 768px)
- Hero: 200px, padding 16px L/R
- Grid: 1 kolumna, gap 20px
- Tytuł: 24px

## Troubleshooting

### Brak pokoi w gridzie
**Problem:** Grid jest pusty mimo że inspiracja ma przypisaną kategorię.

**Rozwiązanie:**
1. Sprawdź czy pokoje mają przypisaną tę samą kategorię (kategoria-pokoi)
2. Sprawdź czy pokoje mają featured image (meta_query wymaga `_thumbnail_id`)
3. Sprawdź paginację - być może jesteś na stronie która nie ma wyników

### Duplikaty w random mode
**Problem:** Te same pokoje pojawiają się na różnych stronach.

**Rozwiązanie:**
1. Sprawdź czy sesja działa: `session_id()` powinno zwrócić ID
2. Wyczyść cookies i sesję przeglądarki
3. Sprawdź `INSPIRATION_ROOMS_SORT` - powinno być 'random'

### Paginacja nie działa
**Problem:** Kliknięcie w stronę 2/3/4 nie zmienia wyników.

**Rozwiązanie:**
1. Sprawdź permalinki: Ustawienia > Permalinki > Zapisz
2. Sprawdź czy `get_query_var('paged')` zwraca wartość
3. Sprawdź czy `$rooms_query->max_num_pages > 1`

### Breadcrumbs pokazują błędne linki
**Problem:** Link do archiwum inspiracji nie działa.

**Rozwiązanie:**
1. Sprawdź czy istnieje archiwum dla post type 'inspiracja'
2. Możesz ustawić custom URL dla breadcrumbs

## Performance Tips

1. **Caching** - Rozważ cache dla query pokoi (transients)
2. **Image optimization** - Użyj responsive images (srcset)
3. **Lazy loading** - Featured images mają `loading="lazy"`
4. **Session cleanup** - Wyczyść stare sesje cron job

## Future Enhancements

- [ ] Real filters functionality (backend integration)
- [ ] Related styles gallery (replace placeholder)
- [ ] AJAX pagination
- [ ] Infinite scroll option
- [ ] Favorites/save functionality
- [ ] Share buttons

## Changelog

### v1.0.0 (2025-01-XX)
- Initial release
- Hero section with ACF image
- Intro text section
- Rooms grid 3×4
- Pagination
- Session tracking for random mode
- Filters mockup
- Related styles placeholder
- Full responsive design