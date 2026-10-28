---
kind: component
group: Form
components: [color-picker]
---
# Color picker · `<x-ui::color-picker>`

Picks a color by dragging, from a preset or by typing the hex. Always outputs hex, ready to save.

## Examples

### Project color

Click the swatch to open the panel, or type the hex straight into the input.

```blade
<x-ui::color-picker label="Project color" value="#2563EB" class="max-w-xs" />
```

### Inline panel, with opacity

inline shows the panel open; alpha adds the opacity track and the value becomes #RRGGBBAA. The eyedropper shows in browsers that support it.

```blade
<x-ui::color-picker inline alpha value="#7C3AED" />
```

## API

wire:model receives the hex (#2563EB). With name, a hidden input submits the value in plain forms.

### `x-ui::color-picker`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Like other fields. |
| `value` | string | `#18181B` | Initial color in hex. |
| `alpha` | boolean | `false` | Opacity track; outputs #RRGGBBAA. |
| `presets` | array | `8 colors` | Preset colors; [] hides them. |
| `inline` | boolean | `false` | Panel only, no input. |
| `name` | string | `null` | Hidden input for plain forms. |

## Accessibility

- The saturation and brightness area is role="slider" and moves with the arrow keys.
- Hue and opacity are native range inputs with labels.
- Presets are a radiogroup with the hex as name.
- The hex is always shown as text, not only as a color.
