# Number input · `<x-ui::number-input>`

Number with − and +, limits, step and unit: milestone hours, installments, discount.

## Examples

### With unit and limits

min, max and step bound the value; the buttons disable at the limits. ↑↓ on the keyboard also adjust it.

```blade
<div class="grid w-full max-w-xl gap-4 sm:grid-cols-3">
    <x-ui::number-input label="Milestone hours" value="60" min="0" max="200" step="5" unit="h" />
    <x-ui::number-input label="Installments" value="5" min="1" max="12" />
    <x-ui::number-input label="Discount" value="7.5" min="0" max="30" step="0.5" unit="%" />
</div>
```

### Sizes and error

sm in tables and filters; the validation error comes from wire:model, like the other fields.

```blade
<div class="grid w-full max-w-md gap-4">
    <x-ui::number-input size="sm" value="3" min="0" unit="days" />
    <x-ui::number-input label="Deadline" value="0" min="0" unit="days" error="The deadline must be greater than zero." />
</div>
```

## API

With wire:model, every click fires input and change; use wire:model.live to save right away.

### `x-ui::number-input`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Same as the other fields. |
| `min · max` | number | `null` | Limits; the buttons disable at them. |
| `step` | number | `1` | Step for the buttons and arrow keys. |
| `unit` | string | `null` | Text after the value. |
| `size` | sm \| base \| lg | `base` | Height of 36, 44 or 52 px. |

## Accessibility

- The field is role="spinbutton" with aria-valuemin, aria-valuemax and aria-valuenow.
- ↑↓ adjust the value; the − and + buttons are out of the Tab order so focus is not duplicated.
- Buttons have aria-label "Decrease" and "Increase" followed by the field label (translated with the app locale).
- A value outside the limits is corrected when leaving the field.
