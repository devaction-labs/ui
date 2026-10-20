# Date range · `<x-ui::date-range>`

Date range with two months side by side, shortcuts and an optional time. The choice only counts after Apply.

## Examples

### Report period

The field opens the panel; the first click sets the start and the second the end (the band follows the mouse). Cancel or Esc undoes it.

```blade
<x-ui::date-range label="Period" :value="['start' => '2026-10-01', 'end' => '2026-10-15']" today="2026-10-15" :presets="[['label' => 'SGE milestone M3', 'start' => '2026-10-01', 'end' => '2026-10-31']]" />
```

### With time

with-time adds a start and end time in 15-minute steps, with the duration. inline keeps the panel open and applies every choice.

```blade
<x-ui::date-range
    inline
    with-time
    time-heading="Time log entry"
    timezone="America/New_York (UTC−4)"
    today="2026-10-15"
    :value="['start' => '2026-10-01', 'end' => '2026-10-15', 'start_time' => '09:00', 'end_time' => '11:15']"
/>
```

## API

wire:model receives ["start" => "2026-10-01", "end" => "2026-10-15"] and, with with-time, also start_time and end_time ("09:00"). The value only changes on Apply, so wire:model.live calls the server once per choice. Shortcut labels follow the app locale.

### `x-ui::date-range`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label · description · error` | string | `null` | Same as the other fields. |
| `value` | array | `null` | Start and end in ISO (and times with with-time). |
| `presets` | array | `[]` | Extra shortcuts: [["label" => …, "start" => …, "end" => …]]. |
| `with-time · step` | boolean · int | `false · 15` | Start and end time, in steps of step minutes. |
| `time-heading · timezone` | string | `Time · null` | Heading of the time column and the timezone shown. |
| `min · max` | string | `null` | Limits in ISO. |
| `inline` | boolean | `false` | Panel always open; every choice applies. |
| `name` | string | `null` | Hidden inputs name[start], name[end]… |
| `today · locale` | string | `today · app locale` | Reference date for the shortcuts, and the language. |

## Accessibility

- The panel is a role="dialog"; Esc and clicking outside cancel and return focus to the field.
- Each day has an aria-label with the date in words and aria-pressed on the range ends; today has aria-current="date".
- The times are a role="listbox" with aria-selected.
- On phones one month shows at a time and the shortcuts scroll horizontally.
