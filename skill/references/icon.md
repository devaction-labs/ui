---
kind: component
group: Primitives
components: [icon]
---
# Icon · `<x-ui::icon>`

Hugeicons Stroke Rounded icons as inline SVG (24×24, stroke 1.5, `currentColor`), so they follow the text color and the theme.

## Examples

### Basic

```blade
<x-ui::icon name="sparkles" />
<x-ui::icon name="git-pull-request" class="size-4 text-muted" />
```

### Meaningful icon

Without `label` the icon is decorative and hidden from screen readers. With it, it becomes an image.

```blade
<x-ui::icon name="github" label="GitHub" />
```

### Missing icon

Each icon is a file in `icons/{name}.blade.php`. If one is missing, generate it instead of drawing SVG by hand:

```bash
php artisan ui:icon calendar-add-01
```

## API

### `x-ui::icon`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | required | Hugeicons name in kebab-case; resolves `ui::icons.{name}`. |
| `label` | string | `null` | Accessible name; sets `role="img"` and `aria-label`. |
| `strokeWidth` | number | `1.5` | SVG stroke width. |

Default size is `size-5`; pass any `size-*` class to change it. An app can override an icon in `resources/views/vendor/ui/icons/`.

## Accessibility

- Decorative by default: `aria-hidden="true"`.
- An icon-only button needs the `aria-label` on the button; use `label` here only for a standalone meaningful icon.
- Never use an icon as the only signal of status: add text.
