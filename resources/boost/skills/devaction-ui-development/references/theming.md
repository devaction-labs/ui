# Theming · tokens and dark mode

The kit reads CSS variables only. `:root` holds the light theme, `.dark` the dark one; use `.light` inside a `.dark` block to switch back locally.

| Token class | Use |
| --- | --- |
| `bg-frame` | App background behind panels |
| `bg-panel` / `bg-card` | Panels and cards |
| `bg-wrap` / `bg-subtle` | Subtle fills, hover states, chips |
| `text-ink` / `text-ink-2` / `text-ink-3` / `text-muted` | Text from strongest to weakest |
| `border-line` / `border-line-soft` / `border-line-strong` / `border-line-hover` | Borders |
| `bg-ai` / `bg-ai-soft` / `text-accent` | AI features and highlights (amber) |
| `bg-ok-bg text-ok-fg border-ok-line` | Success |
| `bg-warn-bg text-warn-fg border-warn-line` | Warning |
| `bg-risk-bg text-risk-fg border-risk-line` | Danger |
| `bg-info-bg text-info-fg border-info-line` | Info |
| `bg-navy` | Brand tile |

## Themes

Two attributes theme the kit, on `<html>` or on any element (a section, a modal):

```blade
<html lang="en" data-ui-base="slate" data-ui-accent="blue">
```

- `data-ui-base`: zinc (default), slate, gray, neutral, stone, mauve, olive, mist, taupe — backgrounds, borders, text.
- `data-ui-accent`: red, orange, amber, yellow, lime, green, emerald, teal, cyan, sky, blue, indigo, violet, purple, fuchsia, pink, rose — primary actions. Omit it for graphite primary.
- Coherent pairs: slate+blue/indigo, mist+teal/cyan/sky, olive+lime/green/emerald, mauve+violet/purple/fuchsia/pink, stone+red/orange/amber/yellow, taupe+rose.
- For your own primary UI use `bg-primary text-on-primary`, `bg-primary-soft`, `text-accent`, `ring-ring` — never a hard-coded palette color, so it follows the theme.

Dark mode: toggle the `dark` class on `<html>` (persist it in `localStorage` and apply it in an inline script in `<head>` to avoid a flash). Never add `dark:` utilities to kit-based views.

Fonts: Geist and Geist Mono (`font-sans`, `font-mono`).
