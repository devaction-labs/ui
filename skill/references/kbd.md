---
kind: component
group: Base
components: [kbd]
---
# Kbd · `<x-ui::kbd>`

Shows a keyboard shortcut in Geist Mono.

## Examples

### Shortcuts

Bordered in search fields and menus; plain inside lists and the command palette.

```blade
<x-ui::kbd>⌘K</x-ui::kbd>
<x-ui::kbd>Esc</x-ui::kbd>
<x-ui::kbd>⇧ ⌘ P</x-ui::kbd>
<x-ui::kbd plain>↵</x-ui::kbd>
```

## API

### `x-ui::kbd`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `plain` | boolean | `false` | No border or background. |

## Accessibility

- Renders <kbd>, which screen readers announce as keyboard input.
- Use the symbol and the name together when the shortcut is essential (⌘ Command).
