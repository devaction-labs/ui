# Calendar · `<x-ui::calendar>`

Event calendar by month or week: deliveries, due dates, meetings. Rendered on the server; navigation works with links or with Livewire.

## Examples

### Month

Today is highlighted; each event is a label in its type’s color. On mobile, events become dots. Clicking a day dispatches calendar-day.

```blade
<x-ui::calendar month="2026-10" today="2026-10-15" selected="2026-10-16" class="w-full"
    :events="[
    ['date' => '2026-10-01', 'title' => 'Kickoff · AYRAM cycle 10', 'color' => 'blue'],
    ['date' => '2026-10-12', 'title' => 'Lesson: Ecto.Multi', 'color' => 'indigo', 'start' => '09:00', 'end' => '09:30'],
    ['date' => '2026-10-13', 'title' => 'SGE daily', 'color' => 'teal', 'start' => '09:00', 'end' => '09:30', 'subtitle' => 'Carlos, Fernando'],
    ['date' => '2026-10-13', 'title' => 'PR #142', 'color' => 'blue', 'start' => '11:00', 'end' => '12:30', 'subtitle' => 'review'],
    ['date' => '2026-10-15', 'title' => 'Amendment 01', 'color' => 'amber', 'start' => '13:00', 'end' => '14:00', 'subtitle' => 'send'],
    ['date' => '2026-10-16', 'title' => 'M3 delivery · SGE', 'color' => 'blue', 'start' => '09:00', 'end' => '11:00'],
    ['date' => '2026-10-16', 'title' => 'Client review', 'color' => 'teal', 'start' => '15:00', 'end' => '16:00'],
    ['date' => '2026-10-18', 'title' => 'AYRAM renewal', 'color' => 'amber'],
    ['date' => '2026-10-23', 'title' => 'M3 with amendment', 'color' => 'blue'],
    ['date' => '2026-10-31', 'title' => 'Hours closing', 'color' => 'amber'],
]"
    :legend="['blue' => 'Milestone delivery', 'amber' => 'Contract', 'teal' => 'Meeting', 'indigo' => 'Lesson']" />
```

### Week

view="week" shows timed events on the hour grid; hours and weekend adjust the grid.

```blade
<x-ui::calendar month="2026-10" view="week" today="2026-10-15" class="w-full" :hours="[8, 17]"
    :events="[
    ['date' => '2026-10-01', 'title' => 'Kickoff · AYRAM cycle 10', 'color' => 'blue'],
    ['date' => '2026-10-12', 'title' => 'Lesson: Ecto.Multi', 'color' => 'indigo', 'start' => '09:00', 'end' => '09:30'],
    ['date' => '2026-10-13', 'title' => 'SGE daily', 'color' => 'teal', 'start' => '09:00', 'end' => '09:30', 'subtitle' => 'Carlos, Fernando'],
    ['date' => '2026-10-13', 'title' => 'PR #142', 'color' => 'blue', 'start' => '11:00', 'end' => '12:30', 'subtitle' => 'review'],
    ['date' => '2026-10-15', 'title' => 'Amendment 01', 'color' => 'amber', 'start' => '13:00', 'end' => '14:00', 'subtitle' => 'send'],
    ['date' => '2026-10-16', 'title' => 'M3 delivery · SGE', 'color' => 'blue', 'start' => '09:00', 'end' => '11:00'],
    ['date' => '2026-10-16', 'title' => 'Client review', 'color' => 'teal', 'start' => '15:00', 'end' => '16:00'],
    ['date' => '2026-10-18', 'title' => 'AYRAM renewal', 'color' => 'amber'],
    ['date' => '2026-10-23', 'title' => 'M3 with amendment', 'color' => 'blue'],
    ['date' => '2026-10-31', 'title' => 'Hours closing', 'color' => 'amber'],
]" />
```

## API

In a Livewire component, pass month-property="month" and view-property="view": the arrows and the view switcher call $set on those properties. Without them, they become links with ?month= and ?view=. Month and weekday names follow the app locale.

### `x-ui::calendar`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `month` | string | `month of today` | Month shown, "2026-10". |
| `view` | month \| week | `month` | View. |
| `events` | array | `[]` | List of ['date', 'title', 'color', 'start', 'end', 'subtitle']. |
| `today · selected` | string | `today · null` | Highlighted ISO dates. |
| `hours · weekend` | array · boolean | `[8, 18] · false` | Week grid. |
| `month-property · view-property` | string | `null` | Livewire properties for navigation. |
| `legend` | array | `[]` | Legend: ['blue' => 'Delivery', …]. |

## Accessibility

- The month view is role="grid"; each day is a button with the full date and the number of events.
- The current month is announced on change (aria-live) and the view switcher is a radiogroup.
- Events have text, not just color; on mobile the title is still available to screen readers.
