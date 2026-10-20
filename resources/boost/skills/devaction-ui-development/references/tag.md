# Tag · `<x-ui::tag>`

A status label in uppercase Geist Mono, with border, background and text in the same tone.

## Examples

### Status

The five palette tags. Amber always means "the AI did it", red means "someone is missing".

```blade
<x-ui::tag color="risk">At risk</x-ui::tag>
<x-ui::tag color="warn">AI reviewed</x-ui::tag>
<x-ui::tag color="ok">On track</x-ui::tag>
<x-ui::tag color="info">Learning</x-ui::tag>
<x-ui::tag>Planned</x-ui::tag>
```

### With icon and no uppercase

For PR numbers and versions, turn uppercase off.

```blade
<x-ui::tag color="risk" icon="git-pull-request" :uppercase="false">#142</x-ui::tag>
<x-ui::tag :uppercase="false">since v0.1</x-ui::tag>
```

## API

### `x-ui::tag`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `color` | plain \| risk \| warn \| ok \| info | `plain` | Maps to the status tokens. |
| `icon` | string | `null` | Hugeicons icon before the text. |
| `uppercase` | boolean | `true` | Uppercase with 0.06em tracking. |

## Accessibility

- Text always comes with the color.
- Icons are decorative (aria-hidden).
- Minimum 4.5:1 contrast in light and dark themes.
- For status inside running text, use Badge.
