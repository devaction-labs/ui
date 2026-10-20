# Stat · `<x-ui::stat>`

A highlighted number with a label, change and context, for dashboards and summary strips.

## Examples

### Stats grid

stat.group lays them out in columns. color paints the value: amber means "the AI did it", red means "someone is missing". inverted is the dark highlight.

```blade
<x-ui::stat.group columns="4" class="w-full">
    <x-ui::stat label="Ready to ship" value="78.4" unit="%" trend="+6 pts" description="since Monday" />
    <x-ui::stat label="AI reviewed, waiting on you" value="29" color="ai" description="scope items" />
    <x-ui::stat label="No PR yet" value="17" color="risk" description="3 due this week" />
    <x-ui::stat label="Highlight" value="4" variant="inverted" description="PRs awaiting review" />
</x-ui::stat.group>
```

### Trend

Direction comes from the sign: + goes up in green, - goes down in red. trend:inverse when going down is good.

```blade
<x-ui::stat.group columns="3" class="w-full">
    <x-ui::stat label="Billed this month" value="$18.2" unit="k" trend="+12%" description="vs. September" />
    <x-ui::stat label="Hours in the bank" value="62" unit="h" trend="-8 h" description="vs. last week" />
    <x-ui::stat label="Items without a PR" value="17" trend="-3" trend:inverse description="lower is better" />
</x-ui::stat.group>
```

### Summary strip

size="sm" with stat.group divided becomes the numbers strip on the kit home page.

```blade
<x-ui::stat.group columns="5" divided class="w-full">
    <x-ui::stat value="76" label="components" size="sm" />
    <x-ui::stat value="7" label="groups" size="sm" />
    <x-ui::stat value="0" label="UI dependencies" size="sm" />
    <x-ui::stat value="4" label="Livewire" size="sm" />
    <x-ui::stat value="v4" label="Tailwind CSS" size="sm" />
</x-ui::stat.group>
```

### With icon and no border

icon sits next to the label. variant="plain" to use it inside another card.

```blade
<x-ui::panel heading="SGE · Church Management" class="w-full max-w-md">
    <div class="grid grid-cols-2 gap-4">
        <x-ui::stat label="Open PRs" value="6" icon="git-pull-request" variant="plain" />
        <x-ui::stat label="Lessons" value="3" icon="mortarboard-02" color="ai" variant="plain" />
    </div>
</x-ui::panel>
```

## API

The default slot goes right after the value, for rich suffixes.

### `x-ui::stat`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `null` | Monospace label above the value (below it in size sm). |
| `value` | string | `null` | Already formatted value: "78.4", "$18.2". |
| `unit` | string | `null` | Smaller gray unit after the value. |
| `description` | string | `null` | Context below the value. |
| `trend` | string | `null` | Change ("+6 pts"); direction comes from the sign. |
| `trend:direction` | up \| down \| flat | `from the sign` | Forces the arrow direction. |
| `trend:inverse` | boolean | `false` | Inverts the colors: going down turns green. |
| `icon` | string | `null` | Icon next to the label. |
| `variant` | card \| plain \| inverted | `card` | inverted: dark highlight background. |
| `size` | base \| sm | `base` | 44 or 28 px value. |
| `color` | default \| ai \| risk \| ok | `default` | Value color. |

### `x-ui::stat.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `columns` | 2 \| 3 \| 4 \| 5 | `4` | Columns from tablet up; 2 on mobile. |
| `divided` | boolean | `false` | A single card with dividers between the stats. |

## Accessibility

- The change is always written out ("+6 pts"); color and arrow only reinforce it.
- Numbers use tabular-nums so their width does not jump when they change.
- The label comes before the value in the DOM, even in size sm, so screen readers get context.
- Minimum 4.5:1 contrast on the label and description, including inverted.
