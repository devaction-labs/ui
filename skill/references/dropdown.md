---
kind: component
group: Overlay
components: [dropdown, menu, menu.checkbox, menu.group, menu.item, menu.radio, menu.radio.group, menu.separator, menu.submenu]
---
# Dropdown · `<x-ui::dropdown>`

Action menu that opens from a button. Arrow keys, Home, End and the first letter navigate; Esc closes and returns to the button.

## Examples

### Sort and actions

menu.radio.group for a single choice, menu.separator between groups, kbd for shortcuts and variant="danger" for what cannot be undone.

```blade
<x-ui::dropdown>
    <x-ui::button icon:trailing="arrow-down-01">By due date</x-ui::button>

    <x-ui::menu>
        <x-ui::menu.radio.group>
            <x-ui::menu.radio value="due" checked>By due date</x-ui::menu.radio>
            <x-ui::menu.radio value="amount">By amount</x-ui::menu.radio>
            <x-ui::menu.radio value="risk">By risk</x-ui::menu.radio>
        </x-ui::menu.radio.group>
        <x-ui::menu.separator />
        <x-ui::menu.item icon="download-04" kbd="⌘E">Export CSV</x-ui::menu.item>
        <x-ui::menu.item icon="archive-02" variant="danger">Archive milestone</x-ui::menu.item>
    </x-ui::menu>
</x-ui::dropdown>
```

### Row menu

The ⋯ button in tables. align="end" aligns to the right; groups with headings and a submenu.

```blade
<x-ui::dropdown align="end">
    <x-ui::button variant="ghost" icon="more-horizontal" tooltip="More actions" />

    <x-ui::menu>
        <x-ui::menu.group heading="PR #142">
            <x-ui::menu.item icon="view">Open on GitHub</x-ui::menu.item>
            <x-ui::menu.item icon="copy-01" kbd="⌘C">Copy link</x-ui::menu.item>
        </x-ui::menu.group>
        <x-ui::menu.separator />
        <x-ui::menu.submenu heading="Move to" icon="folder-01">
            <x-ui::menu.item>SGE · Church Management</x-ui::menu.item>
            <x-ui::menu.item>Hub API</x-ui::menu.item>
            <x-ui::menu.item>AYRAM web</x-ui::menu.item>
        </x-ui::menu.submenu>
        <x-ui::menu.item icon="user-add-01" suffix="3">Assign</x-ui::menu.item>
        <x-ui::menu.separator />
        <x-ui::menu.item icon="delete-02" variant="danger">Delete</x-ui::menu.item>
    </x-ui::menu>
</x-ui::dropdown>
```

### Filters that stay open

menu.checkbox checks and unchecks. keep-open on the menu lets you pick several without closing.

```blade
<x-ui::dropdown>
    <x-ui::button icon="filter" icon:trailing="arrow-down-01">Status</x-ui::button>

    <x-ui::menu keep-open>
        <x-ui::menu.checkbox checked>On track</x-ui::menu.checkbox>
        <x-ui::menu.checkbox checked>At risk</x-ui::menu.checkbox>
        <x-ui::menu.checkbox>Planned</x-ui::menu.checkbox>
        <x-ui::menu.checkbox disabled>Archived</x-ui::menu.checkbox>
    </x-ui::menu>
</x-ui::dropdown>
```

## API

x-ui::dropdown is the same one used by Popover (position, align, gap, offset, hover, wire:model). menu.checkbox and menu.radio.group accept wire:model.

### `x-ui::menu`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `keep-open` | boolean | `false` | Does not close when an item is picked. |

### `x-ui::menu.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `icon` | string | `null` | Icon before the text. |
| `icon:trailing` | string | `null` | Icon at the end. |
| `kbd` | string | `null` | Shortcut at the end. |
| `suffix` | string | `null` | Text at the end, like a count. |
| `variant` | default \| danger | `default` | danger in red. |
| `href` | string | `null` | Turns into a link. |
| `disabled` | boolean | `false` | Skipped by navigation. |
| `keep-open` | boolean | `false` | Does not close on click. |

### `x-ui::menu.submenu`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `—` | Text of the item that opens the submenu. |
| `icon` | string | `null` | Item icon. |

### `x-ui::menu.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Mono heading of the group. |

### `x-ui::menu.checkbox`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `checked` | boolean | `false` | Starts checked. Accepts wire:model. |
| `keep-open` | boolean | `false` | Does not close when checked. |

### `x-ui::menu.radio.group · menu.radio`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | string | `text` | Option value; the group accepts wire:model. |
| `checked` | boolean | `false` | Option selected on first load. |

## Accessibility

- role="menu", "menuitem", "menuitemcheckbox" and "menuitemradio", with aria-checked.
- On open, focus moves to the first item; arrow keys, Home, End and the first letter navigate.
- A submenu opens with → and closes with ←; Esc closes everything and returns focus to the button.
- Disabled items are skipped by keyboard navigation.
