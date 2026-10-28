---
kind: component
group: Primitives
components: [heading, text, eyebrow]
---
# Typography · `<x-ui::heading>`, `<x-ui::text>`, `<x-ui::eyebrow>`

Three text primitives with the kit's sizes and tokens: titles, supporting text and the uppercase mono section label.

## Examples

### Heading

`level` turns it into `<h1>`…`<h6>`; without it, it is a `<div>`.

```blade
<x-ui::heading level="1" size="xl">Contracts</x-ui::heading>
<x-ui::heading level="2" size="lg">Update profile</x-ui::heading>
<x-ui::heading>Billing</x-ui::heading>
```

### Text

```blade
<x-ui::text class="mt-2">Change your personal details.</x-ui::text>
<x-ui::text variant="strong">Total: $1,290</x-ui::text>
<x-ui::text size="sm" variant="subtle">Updated 2 minutes ago</x-ui::text>
```

### Eyebrow

```blade
<x-ui::eyebrow>How it works</x-ui::eyebrow>
<x-ui::eyebrow accent>Web, mobile and tablet</x-ui::eyebrow>
```

## API

### `x-ui::heading`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `size` | `base` \| `lg` \| `xl` | `base` | Font size. |
| `level` | 1 to 6 | `null` | Renders `<h1>`…`<h6>`; `null` renders a `<div>`. |

### `x-ui::text`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `size` | `sm` \| `base` \| `lg` | `base` | Font size. |
| `variant` | `default` \| `strong` \| `subtle` | `default` | Tone: secondary, strong ink or muted. |

### `x-ui::eyebrow`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `accent` | boolean | `false` | Uses the accent color instead of muted. |

## Accessibility

- Pick `level` by document outline, not by size; keep one `h1` per page.
- `heading` without `level` is a plain `<div>`: do not use it as the only title of a section.
- Text tones are tokens with enough contrast in both themes.
