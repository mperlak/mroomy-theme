# Przewodnik typografii Mroomy

## Czcionki
- **Nunito** - dla nagłówków, tytułów i tekstu body
- **Nunito Sans** - dla paragrafów i caption

## Dostępne klasy CSS

### Nagłówki (Headlines) - Nunito
- `.headline-1` - 52px/64px, waga 600 (SemiBold)
- `.headline-2` - 52px/64px, waga 800 (ExtraBold)
- `.headline-small-1` - 40px/56px, waga 600
- `.headline-small-2` - 40px/50px, waga 800

### Tytuły (Titles) - Nunito
- `.title-1` - 32px/40px, waga 600
- `.title-2` - 32px/40px, waga 800
- `.title-small-1` - 24px/30px, waga 600
- `.title-small-2` - 24px/30px, waga 800

### Podtytuły (Subtitles) - Nunito
- `.subtitle-1` - 20px/26px, waga 600
- `.subtitle-2` - 20px/26px, waga 800

### Tekst główny (Body) - Nunito
- `.body-1` - 16px/20px, waga 600
- `.body-2` - 16px/20px, waga 800
- `.body-small-1` - 14px/18px, waga 600
- `.body-small-2` - 14px/18px, waga 800
- `.body-super-small-1` - 12px/16px, waga 600
- `.body-super-small-2` - 12px/16px, waga 800

### Paragrafy - Nunito Sans
- `.paragraph-20` - 20px/28px, waga 400
- `.paragraph-20-bold` - 20px/28px, waga 700
- `.paragraph-16` - 16px/22px, waga 400
- `.paragraph-16-bold` - 16px/22px, waga 700
- `.paragraph-14` - 14px/20px, waga 400
- `.paragraph-14-bold` - 14px/20px, waga 700
- `.paragraph-12` - 12px/18px, waga 400
- `.paragraph-12-bold` - 12px/18px, waga 700

### Podpisy (Captions) - Nunito Sans
- `.caption-14` - 14px/18px, waga 400
- `.caption-14-bold` - 14px/18px, waga 700
- `.caption-12` - 12px/16px, waga 400
- `.caption-12-bold` - 12px/16px, waga 700
- `.caption-10` - 10px/14px, waga 400
- `.caption-10-bold` - 10px/14px, waga 700

## Użycie w Tailwind

Możesz też używać klas Tailwind bezpośrednio:

### Rozmiary czcionek
- `text-headline-1`, `text-headline-2`, itd.

### Rodziny czcionek
- `font-nunito` - dla Nunito
- `font-nunito-sans` - dla Nunito Sans

### Wagi czcionek
- `font-regular` - 400
- `font-semibold` - 600
- `font-bold` - 700
- `font-extrabold` - 800

## Przykłady użycia

```html
<!-- Nagłówek główny -->
<h1 class="headline-1">Zaprojektuj z nami pokój PREMIUM</h1>

<!-- Tytuł sekcji -->
<h2 class="title-1">Nasze projekty</h2>

<!-- Paragraf -->
<p class="paragraph-16">Lorem ipsum dolor sit amet...</p>

<!-- Podpis pod obrazkiem -->
<span class="caption-12">Pokój dziecięcy w stylu skandynawskim</span>

<!-- Przycisk -->
<button class="btn-primary">Zobacz więcej</button>
```