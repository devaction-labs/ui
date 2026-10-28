---
kind: component
group: Form
components: [switch]
---
# Switch · `<x-ui::switch>`

Turns something on and off that applies immediately, without a save button. For choices that only apply after saving, use a checkbox.

## Examples

### States, sizes and colors

Off, on, disabled and saving. sm in tables, lg on settings screens. ai in amber for what the AI does.

```blade
<div class="flex flex-wrap items-center gap-6">
    <x-ui::switch aria-label="Off" />
    <x-ui::switch aria-label="On" checked />
    <x-ui::switch aria-label="Disabled" checked disabled />
    <x-ui::switch aria-label="Saving" checked loading />
    <x-ui::switch size="sm" aria-label="Small" checked />
    <x-ui::switch size="lg" aria-label="Large" checked />
    <x-ui::switch color="ai" aria-label="AI" checked />
    <x-ui::switch color="ok" aria-label="Success" checked />
</div>
```

### With label

label and description on the left, switch on the right. With wire:model.live the change saves immediately.

```blade
<div class="flex w-full max-w-lg flex-col divide-y divide-line-soft">
    <x-ui::switch label="Automatic review" description="The AI reviews every PR opened in the watched repositories." class="py-3" checked />
    <x-ui::switch label="Publish as draft" description="Comments only go to GitHub after you approve them." class="py-3" checked />
    <x-ui::switch label="Review Dependabot PRs" description="Dependency updates tend to add noise." class="py-3" />
    <x-ui::switch label="Create lessons from PRs" description="Available on the Agency plan." class="py-3" disabled />
</div>
```

### In a card

For prominent settings, combine it with a card whose border changes when it turns on.

```blade
<div class="grid w-full max-w-lg gap-2.5 sm:grid-cols-2">
    <label class="flex items-center gap-3 rounded-[14px] border border-line bg-card p-3.5 has-checked:border-ink has-checked:bg-subtle">
        <span class="flex size-9 items-center justify-center rounded-[10px] bg-wrap"><x-ui::icon name="mail-01" class="size-[18px]" /></span>
        <span class="flex flex-1 flex-col"><span class="text-sm font-medium">Weekly summary</span><span class="text-xs text-muted">Every Monday at 8 am</span></span>
        <x-ui::switch size="sm" aria-label="Weekly summary" checked />
    </label>
    <label class="flex items-center gap-3 rounded-[14px] border border-line bg-card p-3.5 has-checked:border-ink has-checked:bg-subtle">
        <span class="flex size-9 items-center justify-center rounded-[10px] bg-wrap"><x-ui::icon name="moon-02" class="size-[18px]" /></span>
        <span class="flex flex-1 flex-col"><span class="text-sm font-medium">Dark theme</span><span class="text-xs text-muted">Follows the system</span></span>
        <x-ui::switch size="sm" aria-label="Dark theme" />
    </label>
</div>
```

## API

HTML attributes (name, value, checked, disabled, wire:model) go to the <input role="switch">.

### `x-ui::switch`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description` | string | `null` | Text next to the switch. |
| `size` | sm \| md \| lg | `md` | 32×20, 40×24 and 48×28 px. |
| `color` | default \| ai \| ok | `default` | Color when on. |
| `align` | right \| left | `right` | Switch side relative to the text. |
| `loading` | boolean | `false` | Spinner in the knob while saving; blocks clicks. |

## Accessibility

- Native checkbox with role="switch": screen readers announce "on" or "off".
- A switch without text needs an aria-label.
- Saving sets aria-busy and disables the switch until it finishes.
- Visible focus ring; the animation respects prefers-reduced-motion.
