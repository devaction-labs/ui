---
kind: component
group: Data
components: [chart]
---
# Chart · `<x-ui::chart>`

Line, column, bar, donut and sparkline charts as SVG rendered on the server. No JavaScript library; the hover uses Alpine.

## Examples

### Line with two series

The first series gets the area; the final value sits at the end of each line. Hover to see the tooltip; table shows the same numbers in a table.

```blade
<x-ui::chart
    type="line"
    label="Actual and forecast revenue from March to October"
    :labels="['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct']"
    format="$:valuek"
    table
    :series="[
        ['name' => 'Actual', 'data' => [38, 42, 47, 45, 52, 58, 61, 66]],
        ['name' => 'Forecast', 'data' => [40, 44, 46, 50, 54, 56, 60, 64], 'color' => '#C2850F'],
    ]"
/>
```

### Columns

Columns up to 24 px wide with rounded tops. The value shows on the last column and on the one under the pointer.

```blade
<x-ui::chart
    type="column"
    label="PRs reviewed per week over the last 8 weeks"
    :labels="['w34', 'w35', 'w36', 'w37', 'w38', 'w39', 'w40', 'w41']"
    :series="[['name' => 'PRs', 'data' => [12, 18, 15, 22, 19, 26, 24, 31]]]"
/>
```

### Bars, donut and sparkline

Horizontal bars with the value at the end, a donut with a center and legend, and a sparkline for metric cards.

```blade
<div class="grid w-full items-start gap-8 md:grid-cols-3">
    <x-ui::chart
        type="bar"
        label="Hours per project in October"
        format=":value h"
        :labels="['SGE', 'Ponto Store', 'Clinic', 'School', 'Internal']"
        :series="[['name' => 'Hours', 'data' => [132, 48, 64, 22, 18]]]"
    />
    <x-ui::chart
        type="donut"
        label="M3 scope: 18 done, 4 in review, 3 without PR, 1 out of scope"
        center="69%"
        center-label="done"
        :labels="['Done', 'In review', 'No PR', 'Out of scope']"
        :colors="['#16A34A', '#C2850F', '#DC2626', '#A1A1AA']"
        :series="[['name' => 'Items', 'data' => [18, 4, 3, 1]]]"
    />
    <div class="flex items-end gap-4 rounded-2xl border border-line p-4">
        <div class="flex flex-1 flex-col gap-1.5">
            <span class="font-mono text-[11px] tracking-[0.08em] text-muted">WEEKLY REVENUE</span>
            <span class="text-[28px] font-semibold tracking-[-0.03em] tabular-nums">$18.4k</span>
        </div>
        <x-ui::chart type="sparkline" label="Revenue rising over the last 8 weeks" :series="[['data' => [9, 11, 10, 13, 12, 15, 16, 18]]]" class="w-auto" />
    </div>
</div>
```

## API

Numbers come ready from the server (Livewire or a controller); the component only draws. Colors follow the kit palette and work in the dark theme.

### `x-ui::chart`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `type` | line \| column \| bar \| donut \| sparkline | `line` | Chart type. |
| `labels` | array | `[]` | Axis, bar or slice labels. |
| `series` | array | `[]` | List of ['name' => …, 'data' => […], 'color' => …]; column, bar, donut and sparkline use the first. |
| `format · decimals` | string · int | `:value · 0` | Value template, like "$:valuek". |
| `label` | string | `series names` | Description for screen readers. |
| `legend` | boolean | `automatic` | Legend (line with 2+ series and donut). |
| `table` | boolean | `false` | Button to see the numbers in a table. |
| `colors` | array | `palette` | Slice and bar colors. |
| `center · center-label` | string | `null` | Text in the middle of the donut. |
| `height` | int | `280 (column: 220)` | viewBox height for line and column. |

## Accessibility

- Each chart has role="img" with an aria-label; describe what it shows, not just the title.
- table offers the same numbers in a real table, for screen readers and copying.
- Important values are written on the chart (line end, column, bar), not only on hover.
- Series are told apart by the text legend too, not only by color.
