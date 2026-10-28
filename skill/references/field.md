---
kind: component
group: Primitives
components: [field, label, error, description]
---
# Field · `<x-ui::field>`

Lays out a form field in a column: label, control, supporting text and error. Use it with `x-ui::label`, `x-ui::description` and `x-ui::error` around any input.

## Examples

### Label, control and error

`x-ui::error` with `name` reads the Laravel / Livewire validation error for that property.

```blade
<x-ui::field>
    <x-ui::label for="email">Email</x-ui::label>
    <x-ui::input id="email" type="email" wire:model="email" />
    <x-ui::description>We only use it to send receipts.</x-ui::description>
    <x-ui::error name="email" />
</x-ui::field>
```

### Optional label and trailing hint

```blade
<x-ui::label badge="Optional">Phone</x-ui::label>

<x-ui::label>
    Deadline
    <x-slot:trailing><x-ui::kbd>⌘D</x-ui::kbd></x-slot:trailing>
</x-ui::label>
```

### Inline field

For checkboxes and switches, with the label next to the control.

```blade
<x-ui::field variant="inline">
    <x-ui::switch wire:model="notify" />
    <x-ui::label>Notify me by email</x-ui::label>
</x-ui::field>
```

### Static error message

```blade
<x-ui::error message="The domain after the @ is missing." />
```

## API

### `x-ui::field`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | `block` \| `inline` | `block` | `block` stacks the children; `inline` puts the label beside the control. |

### `x-ui::label`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `badge` | string | `null` | Small tag after the text, e.g. "Optional". |
| `trailing` | slot | `null` | Content pushed to the right edge, like a shortcut. |

### `x-ui::error`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | Property whose first validation error is shown. |
| `message` | string | `null` | Fixed message; falls back to the slot. Nothing renders when empty. |

### `x-ui::description`

No props. Supporting text in the muted tone; attributes go to the `<p>`.

## Accessibility

- Pair every label with its control through `for` / `id`.
- `x-ui::error` renders `role="alert"` with an icon and text, so the error is not conveyed by color alone.
- Keep the label visible; use `x-ui::description` for help instead of placeholders.
