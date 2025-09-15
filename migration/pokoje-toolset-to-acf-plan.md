# Plan migracji z Toolset do ACF PRO - Pokoje i Inspiracje

## 1. Analiza obecnej struktury

### Custom Post Types

#### 1.1. CPT "pokoje-dla-dzieci"
- **Nazwa:** pokoje-dla-dzieci
- **Etykieta:** Pokoje
- **Hierarchiczny:** Tak
- **Publiczny:** Tak

#### 1.2. CPT "inspiracja"
- **Nazwa:** inspiracja
- **Etykieta:** Inspiracje
- **Hierarchiczny:** Tak
- **Publiczny:** Tak

#### 1.3. CPT "inspiracja-pokoj" (intermediary)
- **Post type pośredniczący** dla relacji many-to-many między inspiracja i pokoje-dla-dzieci
- Utworzony automatycznie przez Toolset

### Pola Toolset

#### Pola CPT "pokoje-dla-dzieci" (prefix wpcf-):
1. **wpcf-sku** - SKU projektu (textfield)
2. **wpcf-dlugi-opis** - Długi opis (WYSIWYG)
3. **wpcf-krotki-opis** - Krótki opis (WYSIWYG)
4. **wpcf-wizualizacje** - Wizualizacje (repetitive image field)
5. **wpcf-opis** - Opis projektu (WYSIWYG)
6. **wpcf-zdjecia** - Zdjęcia (repetitive image field)
7. **wpcf-krotki** - Krótki opis alternatywny (WYSIWYG)
8. **wpcf-wymiary** - Wymiary produktu (textarea)
9. **wpcf-kolor** - Kolor produktu (textarea)
10. **wpcf-header-picture** - Zdjęcie w nagłówku (image)
11. **wpcf-header-text** - Tekst w nagłówku (WYSIWYG)
12. **wpcf-subheader-text** - Tekst pod nagłówkiem (WYSIWYG)
13. **wpcf-header** - Nagłówek (textfield)
14. **wpcf-material** - Materiał (textarea)
15. **wpcf-dodatkowe-informacje** - Dodatkowe informacje (textarea)
16. **wpcf-czas-dostawy** - Czas dostawy (textarea)
17. **wpcf-na-zamowienie** - Na zamówienie (checkbox)

#### Pola CPT "inspiracja":
1. **wpcf-header** - Nagłówek (textfield)
2. **wpcf-header-text** - Tekst w nagłówku (WYSIWYG)
3. **wpcf-header-picture** - Zdjęcie w nagłówku (image)
4. **wpcf-subheader-text** - Tekst pod nagłówkiem (WYSIWYG)
5. **wpcf-dodatkowy-opis-indodatkowy-opis-inspiracjaspiracja** - Dodatkowy opis (WYSIWYG)

### Taksonomie (wszystkie muszą zostać zmigrowane)
1. **kategoria-pokoi** - Kategorie pokoi (hierarchiczna)
   - Przypisana do: inspiracja, pokoje-dla-dzieci, page
2. **przeznaczenie** - Przeznaczenie (hierarchiczna)
   - Przypisana do: pokoje-dla-dzieci, page
3. **pokoj-na-poddaszu** - Pokój na poddaszu (płaska)
   - Przypisana do: pokoje-dla-dzieci
4. **elementy-wyposazenia** - Elementy wyposażenia (płaska)
   - Przypisana do: pokoje-dla-dzieci
5. **kolorowy-sufit** - Kolorowe sufity (płaska)
   - Przypisana do: pokoje-dla-dzieci, page

### Relacje Toolset
- **inspiracja-pokoj**: Relacja many-to-many między inspiracja ↔ pokoje-dla-dzieci
  - Używa intermediary post type: inspiracja-pokoj

## 2. Plan migracji - DOPRECYZOWANY

### Faza 1: Przygotowanie
1. ✅ Backup bazy danych
2. ✅ Instalacja ACF PRO
3. ✅ Środowisko testowe

### Faza 2: Rejestracja struktur w kodzie

#### 2.1. Rejestracja CPT w functions.php
```php
// CPT pokoje-dla-dzieci
register_post_type('pokoje-dla-dzieci', [...]);

// CPT inspiracja
register_post_type('inspiracja', [...]);
```

#### 2.2. Rejestracja taksonomii w functions.php
```php
// Wszystkie 5 taksonomii
register_taxonomy('kategoria-pokoi', [...]);
register_taxonomy('przeznaczenie', [...]);
register_taxonomy('pokoj-na-poddaszu', [...]);
register_taxonomy('elementy-wyposazenia', [...]);
register_taxonomy('kolorowy-sufit', [...]);
```

### Faza 3: Konfiguracja ACF

#### 3.1. Grupy pól ACF:
1. **Grupa "Pokoje - Pola główne"** → przypisana do CPT pokoje-dla-dzieci
2. **Grupa "Inspiracje - Pola główne"** → przypisana do CPT inspiracja
3. **Relacja dwukierunkowa** → ACF Relationship field lub Post Object

#### 3.2. Zastąpienie relacji many-to-many:
- **Opcja A:** ACF Bidirectional Relationship
- **Opcja B:** ACF Post Object field (multiple)
- Intermediary post type "inspiracja-pokoj" zostanie usunięty

### Faza 4: Migracja danych

#### 4.1. Skrypt migracji meta fields:
```php
// Konwersja wpcf-* → ACF fields
// Zachowanie taksonomii (bez zmian w wp_term_relationships)
// Migracja relacji z tabeli wp_toolset_relationships → ACF
```

#### 4.2. Specjalne przypadki:
- Repetitive images (wpcf-wizualizacje, wpcf-zdjecia) → ACF Gallery
- Relacje many-to-many → ACF Relationship field

### Faza 5: Weryfikacja (BEZ ZMIANY SZABLONÓW)

Ponieważ nie będziemy zmieniać szablonów, musimy zapewnić kompatybilność wsteczną:

#### 5.1. Wrapper functions w functions.php:
```php
// Funkcja zachowująca kompatybilność
function get_wpcf_field($field_name, $post_id = null) {
    // Mapowanie starego pola na nowe ACF
    $acf_field_map = [
        'wpcf-sku' => 'sku',
        'wpcf-dlugi-opis' => 'dlugi_opis',
        // ... etc
    ];

    if (isset($acf_field_map[$field_name])) {
        return get_field($acf_field_map[$field_name], $post_id);
    }
    return false;
}
```

#### 5.2. Zachowanie meta keys (opcjonalnie):
- Możliwość zachowania starych meta keys dla pełnej kompatybilności
- ACF może zapisywać do custom meta keys

### Faza 6: Testowanie
1. Weryfikacja wyświetlania na froncie (bez zmian w kodzie)
2. Test edycji w panelu admina (nowy interfejs ACF)
3. Sprawdzenie relacji między CPT
4. Test taksonomii

### Faza 7: Finalizacja
1. Dezaktywacja Toolset (po pełnej weryfikacji)
2. Usunięcie intermediary post type "inspiracja-pokoj"
3. Opcjonalnie: czyszczenie starych meta keys

## 3. Mapowanie szczegółowe

### Mapowanie pól Toolset → ACF

#### Pokoje dla dzieci:
| Pole Toolset | Typ ACF | Meta key ACF |
|-------------|---------|--------------|
| wpcf-sku | Text | sku lub wpcf-sku* |
| wpcf-wizualizacje | Gallery | wizualizacje |
| wpcf-zdjecia | Gallery | zdjecia |
| ... (pozostałe jak wcześniej) |

*Możliwość zachowania starych meta keys dla kompatybilności

#### Inspiracje:
| Pole Toolset | Typ ACF | Meta key ACF |
|-------------|---------|--------------|
| wpcf-header | Text | header lub wpcf-header* |
| wpcf-header-text | WYSIWYG Editor | header_text |
| wpcf-header-picture | Image | header_picture |
| ... |

### Mapowanie relacji:
| Toolset | ACF |
|---------|-----|
| inspiracja-pokoj (many-to-many) | ACF Relationship field z sync |

## 4. Kluczowe różnice w podejściu

1. **NIE zmieniamy szablonów** - używamy wrapper functions lub zachowujemy meta keys
2. **Migrujemy WSZYSTKIE taksonomie** - rejestracja w kodzie
3. **Usuwamy intermediary post type** - zastępujemy ACF Relationship
4. **Zachowujemy kompatybilność wsteczną** - wrapper functions dla pól

## 5. Korzyści z migracji

- Czystszy interfejs w adminie (ACF)
- Lepsza wydajność (brak Toolset overhead)
- Łatwiejsze zarządzanie polami przez GUI ACF
- Możliwość eksportu/importu konfiguracji jako JSON
- Lepsze wsparcie i rozwój (ACF jest aktywnie rozwijane)

## 6. Ryzyka i zabezpieczenia

- **Ryzyko:** Utrata relacji many-to-many
  - **Zabezpieczenie:** Dokładny skrypt migracji relacji

- **Ryzyko:** Błędy w wyświetlaniu (stare funkcje Toolset)
  - **Zabezpieczenie:** Wrapper functions dla kompatybilności

- **Ryzyko:** Problem z repetitive fields
  - **Zabezpieczenie:** Konwersja na ACF Gallery z zachowaniem URLs