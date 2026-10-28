---
kind: component
group: Form
components: [segmented, segmented.option]
---
# Segmented · `<x-ui::segmented>`

A single choice among a few options that switch the view instantly: mode, layout, period, theme.

## Examples

### With icon

The default: icon and text. Arrow keys switch the option, like a radio group.

```blade
<x-ui::segmented label="Agent mode">
    <x-ui::segmented.option value="review" icon="git-pull-request" checked>Review</x-ui::segmented.option>
    <x-ui::segmented.option value="plan" icon="task-01">Plan</x-ui::segmented.option>
    <x-ui::segmented.option value="learn" icon="book-02">Learn</x-ui::segmented.option>
</x-ui::segmented>
```

### Icon only, full width and small with counts

Icon only needs an aria-label. full takes the whole width; size="sm" with count suits filters.

```blade
<div class="flex w-full max-w-md flex-col items-start gap-4">
    <x-ui::segmented label="View">
        <x-ui::segmented.option value="list" icon="menu-01" aria-label="List" checked />
        <x-ui::segmented.option value="board" icon="dashboard-square-01" aria-label="Board" />
        <x-ui::segmented.option value="calendar" icon="calendar-03" aria-label="Calendar" />
    </x-ui::segmented>
    <x-ui::segmented label="Theme" full>
        <x-ui::segmented.option value="light" icon="sun-03" checked>Light</x-ui::segmented.option>
        <x-ui::segmented.option value="dark" icon="moon-02">Dark</x-ui::segmented.option>
        <x-ui::segmented.option value="system" icon="computer">System</x-ui::segmented.option>
    </x-ui::segmented>
    <x-ui::segmented label="Period" size="sm">
        <x-ui::segmented.option value="week" count="12" checked>Week</x-ui::segmented.option>
        <x-ui::segmented.option value="month" count="48">Month</x-ui::segmented.option>
        <x-ui::segmented.option value="all" count="310">All</x-ui::segmented.option>
    </x-ui::segmented>
</div>
```

## API

wire:model on x-ui::segmented receives the chosen value. To switch content panels, prefer Tabs.

### `x-ui::segmented`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Group name (aria-label). |
| `name` | string | `automatic` | Radio name, for regular forms. |
| `size` | sm \| base | `base` | 28 or 34 px options. |
| `full` | boolean | `false` | Full width, equal options. |
| `wire:model` | string | `—` | Chosen value. |

### `x-ui::segmented.option`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | string | `text` | Option value. |
| `icon` | string | `null` | Icon before the text. |
| `count` | string | `null` | Monospace count after the text. |
| `checked` | boolean | `false` | Selected on first load. |

## Accessibility

- Native radios in a role="radiogroup": arrows switch the option and Tab enters and leaves the group.
- An icon-only option gets the aria-label on its own radio.
- Visible focus ring on the focused option.
- The selected option changes background and text color, not just shadow.
