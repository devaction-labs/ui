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

Dark mode: toggle the `dark` class on `<html>` (persist it in `localStorage` and apply it in an inline script in `<head>` to avoid a flash). Never add `dark:` utilities to kit-based views.

Fonts: Geist and Geist Mono (`font-sans`, `font-mono`).
