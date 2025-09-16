# Room Components Structure

## Struktura katalogów

```
components/rooms/
├── image/
│   ├── image.php          # Komponent obrazka z obsługą proporcji
│   └── image.css          # Style dla komponentu obrazka
├── room-category-tag/
│   ├── room-category-tag.php  # Komponent tagów kategorii
│   └── room-category-tag.css  # Style dla tagów
├── room-tile/
│   ├── room-tile.php      # Komponent kafelka pokoju
│   └── room-tile.css      # Style dla kafelka
├── rooms-list/
│   ├── rooms-list.php     # Komponent listy/karuzeli pokoi
│   └── rooms-list.css     # Style dla listy
└── README.md             # Ten plik
```

## Użycie komponentów

### Ładowanie komponentu
```php
mroomy_load_room_component('image');
mroomy_load_room_component('room-category-tag');
mroomy_load_room_component('room-tile');
mroomy_load_room_component('rooms-list');
```

### Funkcje pomocnicze (dostępne w inc/rooms-functions.php)
- `mroomy_get_room_thumbnail_data($post_id)` - pobiera dane obrazka wyróżniającego
- `mroomy_parse_room_title($title)` - parsuje tytuł pokoju
- `mroomy_get_room_categories($post_id)` - pobiera kategorie pokoju
- `mroomy_room_is_for($category, $post_id)` - sprawdza kategorię pokoju

## Blok Gutenberg

Lokalizacja: `blocks/rooms-showcase/`

Użycie w edytorze: Blok "Pokoje - Lista" w kategorii "Mroomy"

## Style

Style są automatycznie ładowane gdy:
- Przeglądasz pojedynczy pokój (CPT: pokoje-dla-dzieci)
- Używasz bloku mroomy/rooms-showcase
- Przeglądasz archiwum pokoi

## Status implementacji

- ✅ Issue #1: Struktura katalogów i plików bazowych - ZAKOŃCZONE
- ⏳ Issue #2: Komponent Image - do implementacji
- ⏳ Issue #3: Komponent RoomCategoryTag - do implementacji
- ⏳ Issue #4: Komponent RoomTile - do implementacji
- ⏳ Issue #5: Sekcja RoomsList - do implementacji
- ⏳ Issue #6: Blok Gutenberg - do implementacji
- ⏳ Issue #7: Testowanie i optymalizacja - do implementacji