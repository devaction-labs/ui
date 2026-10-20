# Filter menu · `<x-ui::filter-menu>`

Grouped filters with submenus. The menu stays open while you check options and only filters on Apply.

## Examples

### Project tasks

Hover or use → to open a group. Due date is single choice (single). The number on the button counts the applied filters.

```blade
<div class="h-96 w-full">
    <x-ui::filter-menu :value="['area' => ['backend']]">
        <x-ui::filter-menu.group name="status" label="Status" icon="checkmark-circle-02">
            <x-ui::filter-menu.option value="open" count="5">Open</x-ui::filter-menu.option>
            <x-ui::filter-menu.option value="done" count="3">Done</x-ui::filter-menu.option>
        </x-ui::filter-menu.group>
        <x-ui::filter-menu.group name="area" label="Area" icon="folder-01">
            <x-ui::filter-menu.option value="backend" color="#2563EB">Backend</x-ui::filter-menu.option>
            <x-ui::filter-menu.option value="frontend" color="#7C3AED">Frontend</x-ui::filter-menu.option>
            <x-ui::filter-menu.option value="infra" color="#16A34A">Infra</x-ui::filter-menu.option>
        </x-ui::filter-menu.group>
        <x-ui::filter-menu.group name="owner" label="Owner" icon="user">
            <x-ui::filter-menu.option value="ana">Ana Souza</x-ui::filter-menu.option>
            <x-ui::filter-menu.option value="bruno">Bruno Lima</x-ui::filter-menu.option>
        </x-ui::filter-menu.group>
        <x-ui::filter-menu.group name="due" label="Due date" icon="calendar-03" single>
            <x-ui::filter-menu.option value="overdue">Overdue</x-ui::filter-menu.option>
            <x-ui::filter-menu.option value="week">This week</x-ui::filter-menu.option>
            <x-ui::filter-menu.option value="month">This month</x-ui::filter-menu.option>
        </x-ui::filter-menu.group>
    </x-ui::filter-menu>
</div>
```

## API

wire:model receives ['group' => ['value', …]] only on Apply, so wire:model.live does not make a request per click. The filters-applied event carries the same data.

### `x-ui::filter-menu`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Filter` | Button and heading text. |
| `value` | array | `[]` | Filters applied at the start. |
| `align` | start \| end | `start` | Side the panel opens on. |

### `x-ui::filter-menu.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `—` | Group key in the value. |
| `label · icon` | string | `—` | Item text and icon. |
| `single` | boolean | `false` | Single choice. |

### `x-ui::filter-menu.option`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | string | `text` | Option value. |
| `color · icon · count` | string | `null` | Option details. |

## Accessibility

- role="menu" with menuitem for the groups and menuitemcheckbox or menuitemradio for the options.
- ↑↓ move through items, → opens the submenu, ← goes back and Esc closes one level at a time.
- Each group shows how many options are checked.
- Clear only unchecks; nothing changes in the list until Apply.
