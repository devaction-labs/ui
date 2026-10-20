# Popover · `<x-ui::popover>`

Panel that opens from a button for free-form content: milestone details, filters, a short form. For lists of actions, use Dropdown.

## Examples

### Milestone details

x-ui::dropdown wraps the trigger and the x-ui::popover. arrow draws the design's little arrow.

```blade
<x-ui::dropdown>
    <x-ui::button>
        <span class="size-2 rounded-full bg-missing"></span>
        M3 · Finance
    </x-ui::button>

    <x-ui::popover arrow role="dialog" aria-label="Milestone details">
        <div class="flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
                <strong class="font-semibold">M3 · Finance</strong>
                <x-ui::tag color="risk" size="sm">At risk</x-ui::tag>
            </div>
            <dl class="grid grid-cols-[auto_1fr] gap-x-3.5 gap-y-1.5 text-[13px]">
                <dt class="text-muted">Due</dt><dd>Fri Oct 16, 6 PM</dd>
                <dt class="text-muted">Scope</dt><dd>22 of 32 items</dd>
                <dt class="text-muted">PRs</dt><dd>#142, #145</dd>
            </dl>
            <div class="flex gap-2">
                <x-ui::button variant="primary" size="sm">Open milestone</x-ui::button>
                <x-ui::button size="sm">Change due date</x-ui::button>
            </div>
        </div>
    </x-ui::popover>
</x-ui::dropdown>
```

### Position and alignment

position picks the side; align aligns to the start, center or end of the trigger.

```blade
<div class="flex flex-wrap items-center gap-3 py-24">
    <x-ui::dropdown position="top" align="start">
        <x-ui::button size="sm">Top</x-ui::button>
        <x-ui::popover arrow class="w-56">Opens upward, aligned to the start.</x-ui::popover>
    </x-ui::dropdown>
    <x-ui::dropdown align="center">
        <x-ui::button size="sm">Bottom, center</x-ui::button>
        <x-ui::popover arrow class="w-56">Bottom is the default; centered here.</x-ui::popover>
    </x-ui::dropdown>
    <x-ui::dropdown align="end">
        <x-ui::button size="sm">Bottom, end</x-ui::button>
        <x-ui::popover arrow class="w-56">Aligned to the end of the button.</x-ui::popover>
    </x-ui::dropdown>
    <x-ui::dropdown position="right" align="center">
        <x-ui::button size="sm">Right</x-ui::button>
        <x-ui::popover arrow class="w-48">Beside the button.</x-ui::popover>
    </x-ui::dropdown>
</div>
```

### Card on hover

hover opens without a click, good for person and PR cards.

```blade
<x-ui::dropdown hover gap="10">
    <button type="button" class="flex items-center gap-2.5 rounded-lg px-1 text-sm font-medium">
        <x-ui::avatar name="Carlos André" color="emerald" size="sm" circle />
        Carlos André
    </button>

    <x-ui::popover class="w-72">
        <div class="flex flex-col gap-3">
            <x-ui::avatar name="Carlos André" color="emerald" size="lg" circle badge badge:color="green" badge:label="Online" />
            <div>
                <strong class="text-base font-semibold">Carlos André</strong>
                <p class="text-[13px] text-muted">Dev · SGE and Hub API</p>
            </div>
            <x-ui::stat.group columns="2">
                <x-ui::stat label="Open PRs" value="3" variant="plain" size="sm" />
                <x-ui::stat label="Lessons" value="5" variant="plain" size="sm" />
            </x-ui::stat.group>
        </div>
    </x-ui::popover>
</x-ui::dropdown>
```

## API

The first button or link in the dropdown is the trigger. wire:model on x-ui::dropdown binds open/closed to a Livewire property.

### `x-ui::dropdown`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `position` | bottom \| top \| right \| left | `bottom` | Side the panel opens on. |
| `align` | start \| center \| end | `start` | Alignment with the trigger. |
| `gap` | int | `6` | Distance in px to the trigger. |
| `offset` | int | `0` | Offset in px along the alignment axis (start and end). |
| `hover` | boolean | `false` | Opens on hover. |
| `wire:model` | string | `—` | Binds open/closed to a property. |

### `x-ui::popover`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `arrow` | boolean | `false` | Little arrow pointing to the trigger. |
| `class` | string | `w-80` | Panel width and layout. |

## Accessibility

- The trigger gets aria-haspopup and aria-expanded, updated on open and close.
- Esc closes and returns focus to the trigger; clicking outside also closes.
- For dialog content, pass role="dialog" and aria-label on the popover.
- The enter animation respects prefers-reduced-motion.
