# Reactions · `<x-ui::reactions>`

Emoji reactions under a comment: one pill per emoji with its count, yours highlighted, and a button to add one.

## Examples

### Under a comment

Click a pill to add or remove your reaction; the + button opens the emoji choices. The count already includes your own reaction.

```blade
<x-ui::reactions :reactions="['👍' => 2, '🎉' => 1, '👀' => 1]" :selected="['👍']" />
```

### Your own choices

choices replaces the emojis offered by the + button.

```blade
<x-ui::reactions :reactions="['✅' => 3]" :choices="['✅', '❌', '🤔', '🙏']" />
```

## API

With react, Livewire receives the emoji on every toggle; the component always dispatches reaction-toggled with { emoji, selected } as well, for Alpine or plain JavaScript.

### `x-ui::reactions`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `reactions` | array | `[]` | [emoji => count], including your own reaction. |
| `selected` | array | `[]` | Emojis you already reacted with. |
| `react` | string | `null` | Livewire method called with the emoji. |
| `choices` | array | `👍 🎉 ❤️ 🚀 👀 😄` | Emojis offered by the + button. |

## Accessibility

- Each pill is a button with aria-pressed and an aria-label with the emoji and the count.
- The + button has aria-expanded and an aria-label; Esc and a click outside close the choices.
- Your reaction is marked by aria-pressed and a blue border, not only by color.
