# Date picker · `<x-ui::date-picker>`

Pick a date or a range from a calendar, with shortcuts and limits. The field shows the date in words and how far away it is.

## Examples

### Due date

Single date with shortcuts. min disables the days before the limit. ←→↑↓ move through the days, ↵ picks and Esc closes.

```blade
<x-ui::date-picker label="Due date" value="2026-10-23" min="2026-10-19" class="max-w-sm" />
```

### Range

range: the first click sets the start and the second the end; the band follows the mouse while you hover.

```blade
<x-ui::date-picker label="Milestone period" range :value="['start' => '2026-10-13', 'end' => '2026-10-24']" class="max-w-sm" />
```

### Calendar on the page

inline shows only the calendar, for panels and side filters.

```blade
<x-ui::date-picker inline value="2026-10-16" />
```

## API

wire:model receives "2026-10-16"; with range, an array with start and end. Dates are always ISO, without a timezone. Month and day names follow the app locale (English by default, Portuguese with pt_BR).

### `x-ui::date-picker`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Same as the other fields. |
| `value` | string \| ['start', 'end'] | `null` | ISO date or range. |
| `range` | boolean | `false` | Pick a start and an end. |
| `min · max` | string | `null` | Limits in ISO. |
| `inline` | boolean | `false` | Calendar only, no field. |
| `presets` | boolean | `true` | Shortcuts below the calendar. |
| `relative` | boolean | `true` | Shows "in 3 days" in the field. |
| `placeholder` | string | `Pick a date` | Text when there is no date. |
| `name` | string | `null` | Hidden inputs for regular forms. |
| `locale` | string | `app locale` | Month and day names. |

## Accessibility

- The calendar is role="grid"; only the focused day is in the Tab order and the arrow keys move through the days.
- Each day has a spelled-out aria-label; today has aria-current="date" and the picked day aria-pressed.
- The month name is announced when it changes (aria-live).
- Esc closes and returns focus to the field.
