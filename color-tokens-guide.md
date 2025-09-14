# Przewodnik Color Tokens Mroomy

## System kolorów

System kolorów oparty jest na tokenach z Figmy, które zapewniają spójność wizualną w całej aplikacji.

## Struktura tokenów

### Primary Colors (Różowy - główny kolor marki)
- `primary` - #E20C7B - główny kolor
- `primary-hover` - #830747 - stan hover
- `primary-pressed` - #B30A62 - stan kliknięcia
- `primary-text` - #220212 - tekst na tle primary
- `primary-text-subtle` - #52042D - subtelny tekst
- `primary-text-subtlest` - #830747 - bardzo subtelny tekst
- `primary-on-default` - #FFFFFF - tekst na primary
- `primary-container-bg` - #FEF0F8 - tło kontenerów
- `primary-container-border` - #FCC0DF - obramowanie kontenerów
- `primary-selected-bg` - #FCC0DF - tło wybranego elementu
- `primary-selected-border` - #B30A62 - obramowanie wybranego elementu
- `primary-icon` - #E20C7B - kolor ikon

### Neutral Colors (Szarości)
- `neutral` - #888888 - neutralny kolor
- `neutral-hover` - #555555 - stan hover
- `neutral-pressed` - #6F6F6F - stan kliknięcia
- `neutral-text` - #222222 - główny tekst
- `neutral-text-subtle` - #3D3D3D - subtelny tekst
- `neutral-text-subtlest` - #555555 - bardzo subtelny tekst
- `neutral-on-default` - #FFFFFF - tekst na neutral
- `neutral-container-bg` - #F0F0F0 - tło kontenerów
- `neutral-container-border` - #E0E0E0 - obramowanie kontenerów
- `neutral-field-bg` - #FFFFFF - tło pól formularza
- `neutral-field-border` - #C4C4C4 - obramowanie pól
- `neutral-card` - #FFFFFF - tło kart
- `neutral-icon` - #6F6F6F - kolor ikon

### Information Colors (Niebieski)
- `info` - #5AA0D3 - kolor informacyjny
- `info-hover` - #3D5F79
- `info-pressed` - #4E86AF
- `info-on-default` - #FFFFFF
- `info-container-bg` - #EFF6FB
- `info-container-border` - #D6E7F4
- `info-text` - #283239
- `info-text-subtle` - #314554
- `info-text-subtlest` - #3D5F79
- `info-icon` - #4E86AF

### Accent Colors
- `accent-pink` - #E20C7B
- `accent-pink-subtle` - #F990C6
- `accent-pink-bolder` - #830747
- `accent-beige` - #C39F88
- `accent-blue` - #5AA0D3
- `accent-orange` - #FFA63C

### Semantic Colors
- `success` - #95CA52 - sukces/potwierdzenie
- `warning` - #FFA63C - ostrzeżenie
- `danger` - #ED6565 - błąd/niebezpieczeństwo
- `focus` - #FFDF61 - focus na elemencie

## Użycie w Tailwind

### Kolory tła
```html
<div class="bg-primary">...</div>
<div class="bg-primary-hover">...</div>
<div class="bg-primary-container-bg">...</div>
<div class="bg-neutral-card">...</div>
```

### Kolory tekstu
```html
<p class="text-primary">...</p>
<p class="text-neutral-text">...</p>
<p class="text-neutral-text-subtle">...</p>
<p class="text-info-text">...</p>
```

### Obramowania
```html
<div class="border border-primary-container-border">...</div>
<div class="border border-neutral-field-border">...</div>
```

## Gotowe komponenty

### Przyciski
```html
<button class="btn-primary">Główny przycisk</button>
<button class="btn-secondary">Drugi przycisk</button>
<button class="btn-info">Informacja</button>
<button class="btn-success">Sukces</button>
<button class="btn-warning">Ostrzeżenie</button>
<button class="btn-danger">Niebezpieczeństwo</button>
```

### Karty
```html
<div class="card">Neutralna karta</div>
<div class="card-primary">Karta w kolorze primary</div>
<div class="card-info">Karta informacyjna</div>
```

### Pola formularza
```html
<input type="text" class="field" placeholder="Wpisz tekst...">
```

### Odznaki (Badges)
```html
<span class="badge-primary">Nowe</span>
<span class="badge-info">Info</span>
<span class="badge-success">Aktywne</span>
<span class="badge-warning">Uwaga</span>
<span class="badge-danger">Błąd</span>
```

## Przykład użycia w komponencie

```html
<div class="card">
  <h3 class="title-small-1 text-neutral-text mb-2">Tytuł karty</h3>
  <p class="paragraph-16 text-neutral-text-subtle mb-4">
    Opis karty z użyciem subtelnego tekstu.
  </p>
  <div class="flex gap-2">
    <button class="btn-primary">Akcja główna</button>
    <button class="btn-secondary">Akcja drugorzędna</button>
  </div>
</div>
```

## Wskazówki

1. **Hierarchia**: Używaj wariantów tekstu (text, text-subtle, text-subtlest) do tworzenia hierarchii wizualnej
2. **Stany**: Zawsze definiuj hover i pressed dla interaktywnych elementów
3. **Kontenery**: Używaj container-bg i container-border dla spójnych kontenerów
4. **Formularze**: Stosuj field-bg i field-border dla wszystkich pól formularza
5. **Semantyka**: Używaj kolorów semantycznych (success, warning, danger) dla komunikatów i stanów