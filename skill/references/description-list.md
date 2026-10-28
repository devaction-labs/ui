---
kind: component
group: Base
components: [description-list, description-list.item]
---
# Description list · `<x-ui::description-list>`

Label and value pairs for detail pages: client, contract, repository, owner.

## Examples

### Project details

Label on the left at 120 px and value on the right; on phones the label moves above.

```blade
<x-ui::description-list class="w-full max-w-md">
    <x-ui::description-list.item label="Client">Grace Community Church</x-ui::description-list.item>
    <x-ui::description-list.item label="Model">Milestone-based scope</x-ui::description-list.item>
    <x-ui::description-list.item label="Current milestone">M3 · Financial reports</x-ui::description-list.item>
    <x-ui::description-list.item label="Repository" mono copyable>devaction-labs/sge-api</x-ui::description-list.item>
    <x-ui::description-list.item label="Owner">Fernando Pinto</x-ui::description-list.item>
</x-ui::description-list>
```

### Two columns

columns="2" puts the label on top and splits into two columns from tablet up.

```blade
<x-ui::description-list columns="2" class="w-full max-w-xl">
    <x-ui::description-list.item label="Email">ana@acme.com</x-ui::description-list.item>
    <x-ui::description-list.item label="Phone" mono>+1 (555) 012-3456</x-ui::description-list.item>
    <x-ui::description-list.item label="Company">Acme Inc.</x-ui::description-list.item>
    <x-ui::description-list.item label="Role">Head of Sales</x-ui::description-list.item>
</x-ui::description-list>
```

## API

### `x-ui::description-list`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `columns` | int | `1` | 1 (label beside) · 2 (two columns, label on top). |
| `label-width` | string | `120px` | Width of the label column. |

### `x-ui::description-list.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `—` | Label (dt). |
| `mono` | boolean | `false` | Value in Geist Mono, for codes and IDs. |
| `copyable` | boolean | `false` | Button to copy the value. |

## Accessibility

- Uses <dl>, <dt> and <dd>: screen readers announce the pairs.
- The copy button has aria-label "Copy" + label and announces "Copied".
