---
kind: component
group: Base
components: [progress, progress.segment]
---
# Progress · `<x-ui::progress>`

Shows how much of something is done: milestone scope, hour bank, billed installments or a background process.

## Examples

### Bars with a label

label and show-value build the header; value-label replaces the percentage with text; description goes below.

```blade
<div class="grid w-full max-w-2xl gap-x-7 gap-y-5 sm:grid-cols-2">
    <x-ui::progress value="69" label="M3 scope" show-value description="18 of 26 items done" />
    <x-ui::progress value="62" max="80" color="amber" label="Hour bank · October" value-label="62 h of 80 h" description="18 h left · closes Oct 31" />
</div>
```

### Colors and sizes

ink is the default. amber for what the AI did, green for paid or done, red for risk. size goes from 4 to 8 px.

```blade
<div class="flex w-full max-w-md flex-col gap-4">
    <x-ui::progress value="69" size="lg" />
    <x-ui::progress value="84" color="amber" />
    <x-ui::progress value="40" color="green" />
    <x-ui::progress value="25" color="red" size="sm" />
</div>
```

### Stacked bar

progress.segment splits the bar into parts. Pass the total as value on the parent for screen readers.

```blade
<x-ui::progress value="3" max="5" size="lg" label="SGE contract · billed" value-label="3 of 5 installments" description="Paid: 2 · issued: 1 · to bill: 2" class="max-w-md">
    <x-ui::progress.segment value="1" color="green" />
    <x-ui::progress.segment value="1" color="green" />
    <x-ui::progress.segment value="1" color="amber" />
</x-ui::progress>
```

### Milestones

variant="segmented" draws one pill per step. current paints the step in progress amber.

```blade
<x-ui::progress value="2" max="5" variant="segmented" current size="lg" label="Milestones" value-label="M3 of 5" description="M3 in progress" class="max-w-md" />
```

### Indeterminate

For processes with no known end. The thin bar (sm) suits background tasks.

```blade
<x-ui::progress label="Syncing repositories" color="amber" size="sm" indeterminate description="The AI is reading open PRs" class="max-w-md" />
```

## API

To update it with Livewire, pass the property to :value; the fill animates its width.

### `x-ui::progress`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | number | `0` | Current value, from 0 to max. |
| `max` | number | `100` | Maximum value; in segmented it is the number of steps. |
| `color` | amber \| green \| red \| blue \| indigo | `ink` | Tailwind names mapped to the tokens. |
| `size` | sm \| base \| lg | `base` | 4, 6 and 8 px tall. |
| `variant` | default \| segmented | `default` | segmented: one pill per step. |
| `label` | string | `null` | Label above the bar; becomes the aria-label. |
| `show-value` | boolean | `false` | Shows the percentage on the right. |
| `value-label` | string | `null` | Value text ("62 h of 80 h"); becomes aria-valuetext. |
| `description` | string | `null` | Note below the bar. |
| `current` | boolean | `false` | In segmented, paints the next step amber. |
| `indeterminate` | boolean | `false` | No known value; animates a slice. |

### `x-ui::progress.segment`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `value` | number | `0` | Segment size relative to the parent max. |
| `color` | same colors as progress | `ink` | Segment color. |

## Accessibility

- role="progressbar" with aria-valuenow, aria-valuemin and aria-valuemax.
- value-label becomes aria-valuetext: screen readers say "62 h of 80 h", not just the number.
- Indeterminate omits aria-valuenow, as the ARIA pattern requires.
- The animation stops with prefers-reduced-motion.
