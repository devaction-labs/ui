---
kind: component
group: Base
components: [badge]
---
# Badge · `<x-ui::badge>`

Status in running text, with an optional dot. Never color alone: always with text.

## Examples

### Colors

Green is on track, amber is attention or "the AI did it", red is at risk, blue is waiting.

```blade
<x-ui::badge color="ok" dot>On track</x-ui::badge>
<x-ui::badge color="warn" dot>Attention</x-ui::badge>
<x-ui::badge color="risk" dot>At risk</x-ui::badge>
<x-ui::badge color="info" dot>Waiting</x-ui::badge>
<x-ui::badge>Closed</x-ui::badge>
```

### Sizes and icon

sm in tables and dense rows. The icon goes before the text.

```blade
<x-ui::badge color="risk" size="sm">At risk</x-ui::badge>
<x-ui::badge icon="git-pull-request">PR #214</x-ui::badge>
<x-ui::badge color="warn" icon="sparkles">Reviewed by AI</x-ui::badge>
```

## API

### `x-ui::badge`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `color` | plain \| risk \| warn \| ok \| info | `plain` | Maps to the status tokens. |
| `size` | sm \| base | `base` | Height of 22 and 26 px. |
| `dot` | boolean | `false` | Dot in the text color before the label. |
| `icon` | string | `null` | Hugeicons icon before the text. |

## Accessibility

- The status is always written; color only reinforces it.
- The dot is decorative (aria-hidden).
- Minimum 4.5:1 contrast in light and dark themes.
- For a technical uppercase mono label, use Tag.
