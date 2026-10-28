---
kind: component
group: Form
components: [slider]
---
# Slider · `<x-ui::slider>`

Pick a value or a range by dragging: minimum coverage, review strictness, contract value.

## Examples

### Single value

bubble shows the value above the thumb; unit completes the value in the label.

```blade
<x-ui::slider label="Minimum test coverage" value="70" unit="%" bubble class="max-w-md pt-6" />
```

### With marks

marks creates one step per label; arrow keys move from mark to mark.

```blade
<x-ui::slider label="Review strictness" min="0" max="3" value="1" :marks="['Light', 'Standard', 'Strict', 'Maximum']" class="max-w-md" />
```

### Range

range creates two thumbs; min-distance keeps a minimum distance between them. wire:model receives [min, max].

```blade
<x-ui::slider label="Contract value" range min="0" max="100" step="5" min-distance="10" :value="[20, 70]" prefix="$" unit="k" class="max-w-md" />
```

## API

wire:model on x-ui::slider binds the value (or the range) to a Livewire property; use wire:model.live to filter right away.

### `x-ui::slider`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Label with the value on the right. |
| `min · max · step` | number | `0 · 100 · 1` | Limits and step. |
| `value` | number \| [number, number] | `min` | Initial value; a range receives a pair. |
| `range` | boolean | `false` | Two thumbs. |
| `min-distance` | number | `0` | Minimum distance between the range thumbs. |
| `marks` | array | `[]` | Mark labels, one per step. |
| `bubble` | boolean | `false` | Value bubble above the thumb. |
| `prefix · unit` | string | `''` | Text before and after the displayed value. |
| `disabled` | boolean | `false` | Disables the slider. |

## Accessibility

- Native range input: arrows, Page Up/Down, Home and End work as in the browser.
- aria-valuetext announces the formatted value ("$20k") or the mark name.
- In a range, each thumb has an aria-label ("Minimum" and "Maximum", in the app locale) inside a labelled group.
- Visible focus with an amber outline.
