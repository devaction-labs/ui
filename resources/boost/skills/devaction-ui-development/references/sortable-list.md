---
kind: component
group: Data
components: [sortable-list, sortable-list.item]
---
# Sortable list · `<x-ui::sortable-list>`

A list that reorders by dragging the handle, with the keyboard or with buttons, and announces changes to screen readers.

## Examples

### Project milestones

Drag by the handle, or focus the handle, press Space, move with ↑ ↓ and press Space to drop (Esc cancels). The sentence below the list says where the item landed, in the page language.

```blade
<x-ui::sortable-list label="SGE milestones" class="w-full max-w-lg">
    @foreach ([['m1', 'M1', 'Member registry', 'delivered'], ['m2', 'M2', 'Tithes and offerings', 'delivered'], ['m3', 'M3', 'Financial reports', 'Oct 16'], ['m4', 'M4', 'Schedules and rosters', 'Nov 20'], ['m5', 'M5', 'Members app', 'Dec 18']] as [$key, $code, $name, $due])
        <x-ui::sortable-list.item :key="$key" :label="$name">
            <span class="w-[26px] font-mono text-xs font-normal text-muted">{{ $code }}</span>{{ $name }}
            <x-slot:meta>{{ $due }}</x-slot:meta>
        </x-ui::sortable-list.item>
    @endforeach
</x-ui::sortable-list>
```

### With Livewire

sort calls the method with (key, new position), the same signature as wire:sort. Use wire:key on the items.

```blade
<x-ui::sortable-list sort="moveMilestone" label="Milestones" :buttons="false" class="w-full max-w-lg">
    <x-ui::sortable-list.item key="7" wire:key="milestone-7">Financial reports</x-ui::sortable-list.item>
    <x-ui::sortable-list.item key="8" wire:key="milestone-8">Schedules and rosters</x-ui::sortable-list.item>
</x-ui::sortable-list>
```

```php
public function moveMilestone(int $id, int $position): void
{
    $milestone = $this->project->milestones()->findOrFail($id);

    $milestone->moveTo($position); // reorders the other milestones
}
```

## API

The reorder event (x-on:reorder) carries detail.key, detail.position and detail.order (all keys in the new order).

### `x-ui::sortable-list`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `sort` | string | `null` | Livewire method called with (key, position). |
| `label` | string | `Sortable list` | List name for screen readers. |
| `buttons` | boolean | `true` | Move up and down buttons on each item. |
| `reorder` | event | `—` | detail: { key, position, order }. |

### `x-ui::sortable-list.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `key` | string | `—` | Identifies the item. |
| `label` | string | `item text` | Name used in announcements. |
| `slot meta` | slot | `—` | Detail on the right (due date, status). |

## Accessibility

- The handle is a "Move …" button with aria-pressed while the item is grabbed.
- Space grabs and drops, ↑ ↓ move, Esc returns the item to its original position.
- Every change is announced in an aria-live region: "M3 moved to position 2 of 5".
- The move up and down buttons are for people who neither drag nor use the keyboard.
