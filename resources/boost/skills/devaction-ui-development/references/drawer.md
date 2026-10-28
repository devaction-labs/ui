---
kind: component
group: Overlay
components: [drawer]
---
# Drawer · `<x-ui::drawer>`

Side panel to edit without leaving the screen: profile, task, contract item. For short, interrupting decisions, use Modal.

## Examples

### Floating

The default: 12 px away from the edges, with heading, description, a scrolling body and a footer with the actions.

```blade
<x-ui::modal.trigger name="drawer-profile">
    <x-ui::button>Edit profile</x-ui::button>
</x-ui::modal.trigger>

<x-ui::drawer name="drawer-profile" heading="Edit profile" description="Update your personal details.">
    <div class="flex items-center gap-3.5">
        <x-ui::avatar name="Alex Nogueira" color="indigo" size="lg" circle />
        <x-ui::button size="sm">Change photo</x-ui::button>
    </div>
    <label class="flex flex-col gap-1.5 font-medium">
        Name
        <input type="text" value="Alex Nogueira" class="h-11 rounded-xl border border-line-strong bg-card px-3.5 font-normal outline-none focus:border-ink focus:ring-4 focus:ring-ink/10" />
    </label>
    <label class="flex flex-col gap-1.5 font-medium">
        About you
        <textarea rows="3" class="rounded-xl border border-line-strong bg-card px-3.5 py-3 font-normal outline-none focus:border-ink focus:ring-4 focus:ring-ink/10">Elixir/Phoenix and Laravel. I like reviewing data modelling and concurrency.</textarea>
    </label>
    <x-slot:footer>
        <x-ui::modal.close><x-ui::button variant="filled">Cancel</x-ui::button></x-ui::modal.close>
        <x-ui::button variant="primary">Save changes</x-ui::button>
    </x-slot:footer>
</x-ui::drawer>
```

### Docked, left and bottom sheet

variant="docked" sticks to the edge. side changes the side; bottom becomes a bottom sheet with a handle, great on phones.

```blade
<x-ui::modal.trigger name="drawer-docked"><x-ui::button size="sm">Docked</x-ui::button></x-ui::modal.trigger>
<x-ui::modal.trigger name="drawer-left"><x-ui::button size="sm">Left</x-ui::button></x-ui::modal.trigger>
<x-ui::modal.trigger name="drawer-sheet"><x-ui::button size="sm">Bottom sheet</x-ui::button></x-ui::modal.trigger>

<x-ui::drawer name="drawer-docked" variant="docked" size="sm" heading="Item 3.2 · Reconciliation" description="M3 · Finance">
    <p class="text-ink-3">In review since yesterday. 2 AI comments waiting for a reply.</p>
</x-ui::drawer>
<x-ui::drawer name="drawer-left" side="left" size="sm" heading="Filters" description="Project, owner and status.">
    <p class="text-ink-3">The filters go here.</p>
</x-ui::drawer>
<x-ui::drawer name="drawer-sheet" side="bottom" heading="New task" description="In SGE · M3 Finance">
    <p class="text-ink-3">A short form for phones.</p>
</x-ui::drawer>
```

## API

Opens and closes like the Modal: modal.trigger, modal.close, wire:model, $modal('name') and the InteractsWithModals trait.

### `x-ui::drawer`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | string | `null` | Name used by modal.trigger and by JS. |
| `heading` | string | `null` | Heading; becomes the dialog's aria-labelledby. |
| `description` | string | `null` | Supporting line below the heading. |
| `variant` | floating \| docked | `floating` | 12 px from the edges or stuck to the edge. |
| `side` | right \| left \| bottom | `right` | Side; bottom is the bottom sheet. |
| `size` | sm \| md \| lg | `md` | Width of 384, 480 or 640 px. |
| `dismissible · escapable · closable` | boolean | `true` | Click outside, Esc and close button. |
| `footer` | slot | `null` | Actions with a divider. |

## Accessibility

- Uses <dialog> with showModal(): the rest of the page becomes inert and focus stays in the panel.
- The heading is linked to the dialog with aria-labelledby.
- Esc closes and focus returns to the opener; page scroll is locked.
- The enter animation respects prefers-reduced-motion.
