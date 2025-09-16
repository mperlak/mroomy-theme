# Instrukcja migracji Toolset → ACF PRO

## Wymagania wstępne
1. **ACF PRO musi być zainstalowany i aktywny**
2. **Toolset musi pozostać aktywny** podczas migracji (potrzebny do odczytu danych)
3. Zrób backup bazy danych przed migracją

## Metoda 1: Panel administracyjny (ZALECANA)

### Krok 1: Przygotowanie
1. Skopiuj cały folder `migration/` do motywu
2. Upewnij się, że w `functions.php` jest linia:
   ```php
   require_once get_stylesheet_directory() . '/migration/run-migration-admin.php';
   ```

### Krok 2: Import struktur ACF
Wejdź do **ACF → Tools → Import Field Groups** i zaimportuj kolejno:

1. **Typy postów:** `migration/04-acf-post-types.json`
2. **Taksonomie:** `migration/04-acf-taxonomies.json`
3. **Pola pokoi:** `migration/07-acf-pokoje-fields-clean.json`
4. **Pola inspiracji:** `migration/07-acf-inspiracje-fields-clean.json`
5. **Relacje produktów:** `migration/08-acf-pokoje-produkty-relacja.json`

### Krok 3: Migracja danych
1. Wejdź do **Narzędzia → Migracja Toolset → ACF**
2. Kliknij **"Uruchom migrację pól"** (Krok 1)
3. Po zakończeniu kliknij **"Uruchom migrację produktów"** (Krok 2)

Panel pokazuje wyniki w czasie rzeczywistym i informuje o postępach.

## Metoda 2: WP-CLI (alternatywna)

### Import struktur ACF
Tak samo jak w Metodzie 1 (przez panel ACF)

### Migracja danych przez terminal
```bash
# Migracja podstawowych pól
wp eval-file wp-content/themes/mroomy_s/migration/05-migrate-data.php

# Migracja relacji pokoje-produkty
wp eval-file wp-content/themes/mroomy_s/migration/10-migrate-products-relationships.php
```

## Weryfikacja po migracji

### Sprawdź w panelu admina:
1. Otwórz przykładowy pokój (np. ID 14476)
2. Sprawdź czy widać pole "Produkty z projektu" z przypisanymi produktami
3. Sprawdź czy wszystkie pola ACF zawierają dane
4. Sprawdź czy galerie zdjęć działają

### Sprawdź w bazie danych:
```sql
-- Ile pokoi ma produkty?
SELECT COUNT(DISTINCT post_id) FROM wp_postmeta
WHERE meta_key = 'produkty_z_projektu';

-- Przykładowe produkty dla pokoju
SELECT meta_value FROM wp_postmeta
WHERE post_id = 14476 AND meta_key = 'produkty_z_projektu';
```

### Sprawdź na froncie:
1. Czy strony pokoi się wyświetlają
2. Czy produkty są widoczne (jeśli były wyświetlane)

## Po weryfikacji

### Opcjonalnie możesz:
1. Wyłączyć Toolset Blocks (ale zostaw Types jeśli używasz relacji)
2. Usunąć linię z functions.php (panel migracji):
   ```php
   // Usuń tę linię po zakończeniu migracji
   require_once get_stylesheet_directory() . '/migration/run-migration-admin.php';
   ```
3. Dodać wrapper kompatybilności jeśli używasz funkcji Toolset w szablonach:
   ```php
   require_once get_stylesheet_directory() . '/migration/06-compatibility-wrapper.php';
   ```

## Struktura plików migracyjnych

```
migration/
├── README-MIGRATION-STEPS.md           # Ta instrukcja
├── run-migration-admin.php             # Panel migracji w WP Admin
├── 04-acf-post-types.json             # Definicje CPT dla ACF
├── 04-acf-taxonomies.json             # Definicje taksonomii dla ACF
├── 05-migrate-data.php                # Skrypt migracji pól podstawowych
├── 06-compatibility-wrapper.php        # Wrapper dla funkcji Toolset (opcjonalny)
├── 07-acf-pokoje-fields-clean.json    # Pola ACF dla pokoi
├── 07-acf-inspiracje-fields-clean.json # Pola ACF dla inspiracji
├── 08-acf-pokoje-produkty-relacja.json # Relacja pokoje-produkty
└── 10-migrate-products-relationships.php # Skrypt migracji relacji
```

## Ważne uwagi

⚠️ **NIE wyłączaj Toolset** przed zakończeniem migracji - jest potrzebny do odczytu danych

⚠️ **Kolejność importu jest kluczowa:**
1. Najpierw typy postów i taksonomie (04-*)
2. Potem grupy pól (07-* i 08-*)
3. Na końcu migracja danych (05-* i 10-*)

⚠️ **Zabezpieczenia:**
- Skrypt pomija pokoje które już mają produkty w ACF (chroni przed nadpisaniem)
- Panel migracji wymaga potwierdzenia przed uruchomieniem
- Każdy krok można uruchomić osobno

## Troubleshooting

### Problem: Produkty się nie migrują
- Sprawdź czy Toolset jest aktywny
- Sprawdź czy funkcja `toolset_get_related_posts` jest dostępna
- Sprawdź w bazie: `SELECT COUNT(*) FROM wp_toolset_associations WHERE relationship_id = 1;`

### Problem: Brak panelu migracji
- Sprawdź czy w functions.php jest linia z require_once
- Sprawdź uprawnienia użytkownika (wymaga manage_options)

### Problem: Pola się nie wyświetlają
- Odśwież permalinki (Ustawienia → Bezpośrednie odnośniki → Zapisz)
- Sprawdź czy ACF PRO jest aktywny
- Sprawdź czy importowałeś wszystkie pliki JSON

## Różnice Toolset vs ACF
- Relacja produkty-pokoje w Toolset jest odwrotnie (produkty są parent, pokoje child)
- ACF używa `pokoje-dla-dzieci` jako slug CPT
- Galerie w ACF są przechowywane jako array ID załączników