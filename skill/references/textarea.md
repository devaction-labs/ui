---
kind: component
group: Form
components: [textarea]
---
# Textarea · `<x-ui::textarea>`

Long text: milestone scope, notes, comments. With a counter, an actions footer and auto-grow.

## Examples

### With counter and actions

maxlength turns on the counter, which goes red near the limit. The footer slot holds the actions.

```blade
<x-ui::textarea
    label="Milestone scope"
    description="Describe what is included. The AI uses this text to know what is out of scope."
    maxlength="500"
    rows="5"
    class="w-full"
>
    Tithe entries, cash reconciliation and the treasurer report.
    <x-slot:footer>
        <x-ui::button variant="ghost" size="sm" icon="attachment-01" tooltip="Attach file" />
        <x-ui::button variant="ghost" size="sm" icon="at" tooltip="Mention" />
        <x-ui::button variant="ai" size="sm" icon="sparkles">Improve with AI</x-ui::button>
    </x-slot:footer>
</x-ui::textarea>
```

### Grows with the text

rows="auto" starts at 3 lines and grows up to 12. resize="none" removes the handle.

```blade
<div class="grid w-full max-w-xl gap-4">
    <x-ui::textarea rows="auto" placeholder="Write a note about the contact…" />
    <x-ui::textarea label="Note" error="Write at least 10 characters." resize="none" rows="3">Ok</x-ui::textarea>
</div>
```

## API

Other HTML attributes (name, placeholder, maxlength, wire:model) go to the <textarea>.

### `x-ui::textarea`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Same as input; error comes automatically from wire:model. |
| `rows` | int \| auto | `4` | auto grows with the text up to 12 lines. |
| `resize` | vertical \| horizontal \| both \| none | `vertical` | Resize handle. |
| `maxlength` | int | `null` | Turns on the counter in the footer. |
| `invalid` | boolean | `auto` | Forces the error state. |
| `footer` | slot | `null` | Actions below the text. |

## Accessibility

- Label linked by for/id; help and error by aria-describedby.
- The counter is announced politely (aria-live) and changes color near the limit.
- A field with an error gets aria-invalid and the message is written out with an icon.
- Icon-only footer buttons use tooltip, which also becomes the aria-label.
