---
kind: component
group: Base
components: [tabs, tab, tab.group, tab.panel]
---
# Tabs · `<x-ui::tabs>`

Organizes content into panels in the same area, without leaving the page. Arrows, Home and End switch tabs.

## Examples

### Tabs with panels

tab.group links each tab to the tab.panel with the same name. The underline below the active tab is the default.

```blade
<x-ui::tab.group class="w-full max-w-xl">
    <x-ui::tabs>
        <x-ui::tab name="overview" selected>Overview</x-ui::tab>
        <x-ui::tab name="contract">Contract</x-ui::tab>
        <x-ui::tab name="prs" badge="6">Pull Requests</x-ui::tab>
        <x-ui::tab name="learning">Learning</x-ui::tab>
    </x-ui::tabs>

    <x-ui::tab.panel name="overview" selected class="text-sm text-ink-3">SGE · Church Management, milestone M3 in progress.</x-ui::tab.panel>
    <x-ui::tab.panel name="contract" class="text-sm text-ink-3">5 milestones, 2 installments paid, scope closed.</x-ui::tab.panel>
    <x-ui::tab.panel name="prs" class="text-sm text-ink-3">6 open PRs, 2 with issues flagged by the AI.</x-ui::tab.panel>
    <x-ui::tab.panel name="learning" class="text-sm text-ink-3">3 lessons generated from the team's PRs.</x-ui::tab.panel>
</x-ui::tab.group>
```

### Segmented

variant="segmented" puts the tabs in a box. badge shows a count; badge:color="red" when it is urgent.

```blade
<x-ui::tabs variant="segmented">
    <x-ui::tab name="month" badge="4" badge:color="red">Due this month</x-ui::tab>
    <x-ui::tab name="review" badge="3">In review</x-ui::tab>
    <x-ui::tab name="delivered" badge="2">Delivered</x-ui::tab>
</x-ui::tabs>
```

### Icons, pills and small size

icon before the text. pills for loose filters; size="sm" in toolbars.

```blade
<div class="flex flex-col items-center gap-4">
    <x-ui::tabs variant="segmented" size="sm">
        <x-ui::tab icon="left-to-right-list-bullet">List</x-ui::tab>
        <x-ui::tab icon="kanban">Board</x-ui::tab>
        <x-ui::tab icon="calendar-03">Calendar</x-ui::tab>
    </x-ui::tabs>
    <x-ui::tabs variant="pills" size="sm">
        <x-ui::tab>All</x-ui::tab>
        <x-ui::tab>Mine</x-ui::tab>
        <x-ui::tab>From the AI</x-ui::tab>
        <x-ui::tab disabled>Archived</x-ui::tab>
    </x-ui::tabs>
</div>
```

### Scrollable

scrollable lets the tabs scroll horizontally instead of breaking the page on mobile.

```blade
<x-ui::tabs scrollable class="w-full max-w-sm">
    <x-ui::tab>Profile</x-ui::tab>
    <x-ui::tab>Account</x-ui::tab>
    <x-ui::tab>Billing</x-ui::tab>
    <x-ui::tab>Security</x-ui::tab>
    <x-ui::tab>Notifications</x-ui::tab>
    <x-ui::tab>Integrations</x-ui::tab>
</x-ui::tabs>
```

## API

With Livewire, use wire:model (or wire:model.live) on x-ui::tabs: the active tab becomes a component property.

### `x-ui::tab.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | The x-ui::tabs and the panels. |

### `x-ui::tabs`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | default \| segmented \| pills | `default` | Underline, box or pills. |
| `size` | base \| sm | `base` | sm for dense bars. |
| `scrollable` | boolean | `false` | Horizontal scrolling. |
| `wire:model` | string | `—` | Binds the active tab to a Livewire property. |

### `x-ui::tab`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `index` | Links to tab.panel and wire:model. |
| `icon` | string | `null` | Icon before the text. |
| `icon:trailing` | string | `null` | Icon after the text. |
| `badge` | string | `null` | Monospace count after the text. |
| `badge:color` | red \| amber \| green \| blue | `null` | Count color. |
| `selected` | boolean | `false` | Active tab on first load. |
| `disabled` | boolean | `false` | Excluded from selection and keyboard. |

### `x-ui::tab.panel`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `—` | Same name as the tab. |
| `selected` | boolean | `false` | Visible on first load. |

## Accessibility

- role="tablist", "tab" and "tabpanel", with aria-selected, aria-controls and aria-labelledby.
- Only the active tab is in the Tab order; ←/→, Home and End move focus and select.
- Disabled tabs are skipped by the keyboard.
- The panel receives focus with Tab, with a visible ring, for keyboard-only users.
