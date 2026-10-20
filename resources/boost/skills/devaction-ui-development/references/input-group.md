# Input group · `<x-ui::input-group>`

Text fields with label, icon, help and error, attached add-ons (prefix, suffix, select) and the native select. The base of every form in the kit.

## Examples

### Full field

label, description and icon build the field. With wire:model, the validation error shows up by itself from the field name.

```blade
<div class="grid w-full max-w-xl gap-4">
    <x-ui::input label="Name" icon="user" value="Carlos André" description="How it shows up in reviews." />
    <x-ui::input type="email" label="Email" icon="at" value="carlos@" error="The domain after the @ is missing." />
</div>
```

### States

Read-only with copy, password with show/hide, search with clear and shortcut, and disabled.

```blade
<div class="grid w-full max-w-xl gap-4">
    <x-ui::input label="Webhook secret" value="whsec_8f2c4e1b9a91d" readonly copyable />
    <x-ui::input type="password" label="Password" value="secret-123" viewable />
    <x-ui::input placeholder="Search contacts" icon="search-01" kbd="⌘K" clearable />
    <x-ui::input label="Repository" placeholder="Available after connecting GitHub" disabled />
</div>
```

### Sizes

36, 44 (default) and 52 px.

```blade
<div class="grid w-full max-w-md gap-3">
    <x-ui::input size="sm" placeholder="Small" />
    <x-ui::input placeholder="Medium (default)" />
    <x-ui::input size="lg" placeholder="Large" />
</div>
```

### Add-ons and select

x-ui::input.group attaches prefixes, suffixes and selects to the field. prefix and suffix also exist directly on the input.

```blade
<div class="grid w-full max-w-xl gap-4">
    <x-ui::input.group>
        <x-ui::input.group.prefix>https://</x-ui::input.group.prefix>
        <x-ui::input placeholder="devaction.com" />
    </x-ui::input.group>
    <x-ui::input.group>
        <x-ui::input placeholder="Proposal amount" prefix="$" />
        <x-ui::select class="w-28"><option>USD</option><option>EUR</option></x-ui::select>
    </x-ui::input.group>
    <x-ui::select label="Pipeline stage" placeholder="Pick a stage">
        <x-ui::select.option value="lead">Lead</x-ui::select.option>
        <x-ui::select.option value="proposal">Proposal sent</x-ui::select.option>
        <x-ui::select.option value="negotiation">Negotiation</x-ui::select.option>
    </x-ui::select>
</div>
```

## API

Other HTML attributes (type, name, placeholder, wire:model, x-model, maxlength) go to the <input>.

### `x-ui::input`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Label; linked to the field with for/id. |
| `description` | string | `null` | Help below the field (aria-describedby). |
| `error` | string | `from validation` | Error message; with wire:model it comes from $errors. |
| `icon · icon:trailing` | string | `null` | Icons before and after the text. |
| `kbd` | string | `null` | Shortcut on the right. |
| `prefix · suffix` | string | `null` | Text attached inside the field, like $ or %. |
| `size` | sm \| base \| lg | `base` | Height of 36, 44 or 52 px. |
| `invalid` | boolean | `auto` | Forces the error state. |
| `clearable · copyable · viewable` | boolean | `false` | Clear, copy and show password. |

### `x-ui::select`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Same as the input. |
| `placeholder` | string | `null` | First option, disabled. |
| `size` | sm \| base \| lg | `base` | Height of 36, 44 or 52 px. |

### `x-ui::field · label · description · error`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant (field)` | block \| inline | `block` | Stacked or side by side. |
| `badge (label)` | string | `null` | Marks as "Optional". |
| `name (error)` | string | `null` | Reads the message from $errors. |

### `x-ui::input.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Fields, selects and input.group.prefix / suffix. |

## Accessibility

- Label linked to the field with for/id; help and error linked with aria-describedby.
- A field with an error gets aria-invalid and the message uses role="alert".
- Errors never rely on color alone: the message is written out and has an icon.
- Clear, copy and show password buttons have an aria-label.
