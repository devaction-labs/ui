# Data table · `<x-ui::data-table>`

Data table in a real <table>: sorting, bulk selection, expandable rows, groups, pagination, states, sticky columns and cards on mobile.

## Examples

### Orders with Livewire

The same API as Flux: paginate takes the paginator and, inside a Livewire component, the buttons change page without reloading and scroll to the top (pagination:scroll-to). sortable + wire:click sort on the server; variant="strong" highlights the amount.

```blade
<x-ui::table :paginate="$orders" class="w-full">
    <x-ui::table.columns>
        <x-ui::table.column>Customer</x-ui::table.column>
        <x-ui::table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">Date</x-ui::table.column>
        <x-ui::table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">Status</x-ui::table.column>
        <x-ui::table.column sortable :sorted="$sortBy === 'amount'" :direction="$sortDirection" wire:click="sort('amount')" align="end">Amount</x-ui::table.column>
        <x-ui::table.column><span class="sr-only">Actions</span></x-ui::table.column>
    </x-ui::table.columns>

    <x-ui::table.rows>
        @foreach ($orders as $order)
            <x-ui::table.row :key="$order['id']">
                <x-ui::table.cell>
                    <span class="flex items-center gap-3"><x-ui::avatar size="xs" :name="$order['customer']" color="auto" circle />{{ $order['customer'] }}</span>
                </x-ui::table.cell>
                <x-ui::table.cell class="whitespace-nowrap">{{ $order['date'] }}</x-ui::table.cell>
                <x-ui::table.cell class="py-0">
                    <x-ui::badge size="sm" :color="$order['color']" dot>{{ $order['status'] }}</x-ui::badge>
                </x-ui::table.cell>
                <x-ui::table.cell variant="strong" align="end">{{ $order['amount'] }}</x-ui::table.cell>
                <x-ui::table.cell class="py-0" align="end">
                    <x-ui::button variant="ghost" size="sm" icon="more-horizontal" aria-label="Order actions" />
                </x-ui::table.cell>
            </x-ui::table.row>
        @endforeach
    </x-ui::table.rows>
</x-ui::table>
```

```php
use Livewire\WithPagination;

public string $sortBy = 'date';

public string $sortDirection = 'desc';

public function sort(string $column): void
{
    if ($this->sortBy === $column) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortBy = $column;
        $this->sortDirection = 'asc';
    }
}

#[Computed]
public function orders(): LengthAwarePaginator
{
    return Order::query()
        ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
        ->paginate(5);
}

// in the view: <x-ui::table :paginate="$this->orders"> … @foreach ($this->orders as $order)
```

### Sticky header and bleed

columns sticky pins the header; container:class limits the height of the scrolling area. Inside a card, bleed takes the rows to the edge while keeping the first and last columns aligned with the content.

```blade
<x-ui::card class="w-full max-w-xl">
    <x-ui::card.body>
        <p class="mb-3 text-sm font-semibold">Latest entries</p>
        <x-ui::table bleed container:class="max-h-56">
            <x-ui::table.columns sticky>
                <x-ui::table.column>Description</x-ui::table.column>
                <x-ui::table.column align="end">Hours</x-ui::table.column>
            </x-ui::table.columns>
            <x-ui::table.rows>
                @foreach (range(1, 9) as $day)
                    <x-ui::table.row :key="$day">
                        <x-ui::table.cell>Review of PR #{{ 200 + $day }}</x-ui::table.cell>
                        <x-ui::table.cell align="end" variant="strong">{{ $day % 3 + 1 }} h</x-ui::table.cell>
                    </x-ui::table.row>
                @endforeach
            </x-ui::table.rows>
        </x-ui::table>
    </x-ui::card.body>
</x-ui::card>
```

### Default with sorting

Border and gray header, for main lists. In Livewire, each sortable column calls wire:click="sort('column')" and receives sorted and direction.

```blade
<x-ui::table>
    <x-ui::table.columns>
        <x-ui::table.column sortable sorted>Project</x-ui::table.column>
        <x-ui::table.column sortable>Client</x-ui::table.column>
        <x-ui::table.column sortable align="end">Hours</x-ui::table.column>
        <x-ui::table.column align="end">PRs</x-ui::table.column>
    </x-ui::table.columns>
    <x-ui::table.rows>
        @foreach ([['Sorriso Clínica', 'Dr. Paula', 64, 0, '#C2850F'], ['Igreja Viva', 'Pastor Marcos', 132, 4, '#2563EB'], ['Ponto Store', 'Ana Lima', 48, 1, '#16A34A']] as [$name, $client, $hours, $prs, $color])
            <x-ui::table.row :key="$name">
                <x-ui::table.cell primary>
                    <span class="flex items-center gap-2.5"><span class="size-2 rounded-[2px]" style="background: {{ $color }}"></span>{{ $name }}</span>
                </x-ui::table.cell>
                <x-ui::table.cell>{{ $client }}</x-ui::table.cell>
                <x-ui::table.cell align="end" class="font-mono text-[13px]">{{ $hours }} h</x-ui::table.cell>
                <x-ui::table.cell align="end" @class(['font-mono text-[13px]', 'text-risk-fg' => $prs > 2])>{{ $prs }}</x-ui::table.cell>
            </x-ui::table.row>
        @endforeach
    </x-ui::table.rows>
</x-ui::table>
```

### Bulk selection

selectable adds a checkbox per row and one in the header (with partial state). With something checked, the bulk slot appears over the header. wire:model receives the keys.

```blade
<x-ui::table selectable>
    <x-slot:bulk>
        <x-ui::button size="xs" variant="ghost" icon="user" class="text-on-ink! hover:bg-white/10!">Assign</x-ui::button>
        <x-ui::button size="xs" variant="ghost" icon="calendar-03" class="text-on-ink! hover:bg-white/10!">Change due date</x-ui::button>
    </x-slot:bulk>
    <x-ui::table.columns>
        <x-ui::table.column>Task</x-ui::table.column>
        <x-ui::table.column>Project</x-ui::table.column>
        <x-ui::table.column>Due</x-ui::table.column>
    </x-ui::table.columns>
    <x-ui::table.rows>
        <x-ui::table.row key="1"><x-ui::table.cell primary>Fix N+1 in contributions</x-ui::table.cell><x-ui::table.cell>SGE</x-ui::table.cell><x-ui::table.cell class="text-risk-fg">yesterday</x-ui::table.cell></x-ui::table.row>
        <x-ui::table.row key="2"><x-ui::table.cell primary>Treasurer report</x-ui::table.cell><x-ui::table.cell>SGE</x-ui::table.cell><x-ui::table.cell>Fri 16</x-ui::table.cell></x-ui::table.row>
        <x-ui::table.row key="3"><x-ui::table.cell primary>Checkout with Pix</x-ui::table.cell><x-ui::table.cell>Ponto Store</x-ui::table.cell><x-ui::table.cell>Oct 23</x-ui::table.cell></x-ui::table.row>
    </x-ui::table.rows>
</x-ui::table>
```

### Minimal with actions and an expandable row

variant="minimal" is the shell default: no outer border. expandable reserves the chevron column; a row with a detail slot opens below without leaving the page.

```blade
<x-ui::table variant="minimal" expandable>
    <x-ui::table.columns>
        <x-ui::table.column>Pull request</x-ui::table.column>
        <x-ui::table.column>Repository</x-ui::table.column>
        <x-ui::table.column align="end"><span class="sr-only">Actions</span></x-ui::table.column>
    </x-ui::table.columns>
    <x-ui::table.rows>
        <x-ui::table.row key="142">
            <x-ui::table.cell primary>#142 Donations with Ecto.Multi</x-ui::table.cell>
            <x-ui::table.cell>sge-api</x-ui::table.cell>
            <x-ui::table.cell align="end">
                <x-ui::dropdown align="end">
                    <x-ui::button size="sm" variant="ghost" icon="more-horizontal" aria-label="Actions" />
                    <x-ui::menu>
                        <x-ui::menu.item icon="view">Open</x-ui::menu.item>
                        <x-ui::menu.item icon="delete-02" variant="danger">Archive</x-ui::menu.item>
                    </x-ui::menu>
                </x-ui::dropdown>
            </x-ui::table.cell>
            <x-slot:detail>
                <ul class="list-disc space-y-1 pl-4 text-[13px] text-ink-2">
                    <li>Transaction without rollback when the receipt fails.</li>
                    <li>Members query inside the loop (N+1).</li>
                </ul>
            </x-slot:detail>
        </x-ui::table.row>
        <x-ui::table.row key="139">
            <x-ui::table.cell primary>#139 Receipt uploads</x-ui::table.cell>
            <x-ui::table.cell>sge-web</x-ui::table.cell>
            <x-ui::table.cell align="end">
                <x-ui::button size="sm" variant="ghost" icon="more-horizontal" aria-label="Actions" />
            </x-ui::table.cell>
        </x-ui::table.row>
    </x-ui::table.rows>
</x-ui::table>
```

### Compact, striped and grouped

density="compact" (36 px rows) and striped for lots of numbers. table.group replaces table.rows and opens and closes each group.

```blade
<x-ui::table density="compact" striped>
    <x-ui::table.columns>
        <x-ui::table.column>Date</x-ui::table.column>
        <x-ui::table.column>Task</x-ui::table.column>
        <x-ui::table.column align="end">Hours</x-ui::table.column>
    </x-ui::table.columns>
    <x-ui::table.group heading="SGE · Campo Limpo" count="5h 30" color="#2563EB">
        <x-ui::table.row key="a"><x-ui::table.cell class="font-mono">10/13</x-ui::table.cell><x-ui::table.cell>Fix N+1</x-ui::table.cell><x-ui::table.cell align="end" class="font-mono">2:15</x-ui::table.cell></x-ui::table.row>
        <x-ui::table.row key="b"><x-ui::table.cell class="font-mono">10/14</x-ui::table.cell><x-ui::table.cell>Monthly report</x-ui::table.cell><x-ui::table.cell align="end" class="font-mono">3:15</x-ui::table.cell></x-ui::table.row>
    </x-ui::table.group>
    <x-ui::table.group heading="Ponto Store" count="1h 45" color="#16A34A">
        <x-ui::table.row key="c"><x-ui::table.cell class="font-mono">10/15</x-ui::table.cell><x-ui::table.cell>Checkout with Pix</x-ui::table.cell><x-ui::table.cell align="end" class="font-mono">1:45</x-ui::table.cell></x-ui::table.row>
    </x-ui::table.group>
    <x-slot:footer>
        <span class="font-semibold text-ink">Week total</span>
        <span class="ml-auto font-mono font-semibold text-ink">7:15</span>
    </x-slot:footer>
</x-ui::table>
```

### Pagination, empty and loading

paginator puts pagination in the footer. table.empty and table.skeleton cover the states; in Livewire, show the skeleton with wire:loading.

```blade
<div class="grid w-full gap-4">
    <x-ui::table :paginator="new \Illuminate\Pagination\LengthAwarePaginator(range(1, 4), 23, 4, 2, ['path' => '#'])">
        <x-ui::table.columns>
            <x-ui::table.column>Review</x-ui::table.column>
            <x-ui::table.column>Result</x-ui::table.column>
        </x-ui::table.columns>
        <x-ui::table.rows>
            <x-ui::table.row key="r1"><x-ui::table.cell primary>#140 Member registration</x-ui::table.cell><x-ui::table.cell><x-ui::badge color="ok">Approved</x-ui::badge></x-ui::table.cell></x-ui::table.row>
            <x-ui::table.row key="r2"><x-ui::table.cell primary>#141 Export PDF</x-ui::table.cell><x-ui::table.cell><x-ui::badge color="risk">3 findings</x-ui::badge></x-ui::table.cell></x-ui::table.row>
        </x-ui::table.rows>
    </x-ui::table>
    <x-ui::table>
        <x-ui::table.columns>
            <x-ui::table.column>Contract</x-ui::table.column>
            <x-ui::table.column>Client</x-ui::table.column>
            <x-ui::table.column>Status</x-ui::table.column>
        </x-ui::table.columns>
        <x-ui::table.rows>
            <x-ui::table.skeleton rows="2" columns="3" />
            <x-ui::table.empty icon="file-01" heading="No contracts match this filter" text="Try another status or create a new contract.">
                <x-ui::button size="sm">Clear filters</x-ui::button>
            </x-ui::table.empty>
        </x-ui::table.rows>
    </x-ui::table>
</div>
```

### Sticky columns and cards on mobile

sticky pins the first column (and sticky="end" the last) while the middle scrolls. stack turns each row into a card below 640 px: the primary cell becomes the title and label names the others.

```blade
<div class="grid w-full gap-4">
    <x-ui::table>
        <x-ui::table.columns>
            <x-ui::table.column sticky class="min-w-48">Project</x-ui::table.column>
            @foreach (['Mon 13', 'Tue 14', 'Wed 15', 'Thu 16', 'Fri 17', 'Mon 20', 'Tue 21', 'Wed 22'] as $day)
                <x-ui::table.column align="end" class="min-w-24">{{ $day }}</x-ui::table.column>
            @endforeach
            <x-ui::table.column sticky="end" align="end">Total</x-ui::table.column>
        </x-ui::table.columns>
        <x-ui::table.rows>
            <x-ui::table.row key="sge">
                <x-ui::table.cell sticky primary>SGE · Campo Limpo</x-ui::table.cell>
                @foreach ([2, 3, 1.5, 4, 2, 3, 2.5, 1] as $hours)
                    <x-ui::table.cell align="end" class="font-mono text-[13px]">{{ $hours }}</x-ui::table.cell>
                @endforeach
                <x-ui::table.cell sticky="end" align="end" class="font-mono font-semibold">19</x-ui::table.cell>
            </x-ui::table.row>
        </x-ui::table.rows>
    </x-ui::table>
    <x-ui::table stack>
        <x-ui::table.columns>
            <x-ui::table.column>Project</x-ui::table.column>
            <x-ui::table.column>Client</x-ui::table.column>
            <x-ui::table.column align="end">Hours</x-ui::table.column>
        </x-ui::table.columns>
        <x-ui::table.rows>
            <x-ui::table.row key="s1"><x-ui::table.cell primary>Igreja Viva</x-ui::table.cell><x-ui::table.cell label="Client">Pastor Marcos</x-ui::table.cell><x-ui::table.cell label="Hours" align="end" class="font-mono">132 h</x-ui::table.cell></x-ui::table.row>
            <x-ui::table.row key="s2"><x-ui::table.cell primary>Ponto Store</x-ui::table.cell><x-ui::table.cell label="Client">Ana Lima</x-ui::table.cell><x-ui::table.cell label="Hours" align="end" class="font-mono">48 h</x-ui::table.cell></x-ui::table.row>
        </x-ui::table.rows>
    </x-ui::table>
</div>
```

## API

Sorting and pagination live on the server: keep sortBy and sortDirection in the Livewire component, sort the query and pass the paginator to paginate. Inside a Livewire component (with WithPagination) pagination uses setPage() automatically; outside it, ?page= links. Pagination texts follow the app locale.

### `x-ui::table`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | default \| minimal | `default` | With border and gray header, or clean. |
| `density` | default \| compact | `default` | Rows of 48 or 36 px. |
| `striped` | boolean | `false` | Alternating stripes. |
| `selectable` | boolean | `false` | Checkboxes; wire:model receives the keys. |
| `expandable` | boolean | `false` | Chevron column for rows with a detail slot. |
| `stack` | boolean | `false` | Cards below 640 px. |
| `paginate` | Paginator | `null` | Pagination in the footer (paginator is an alias). |
| `livewire` | boolean | `automatic` | Buttons with setPage(); turns on automatically inside a Livewire component. |
| `pagination:scroll-to` | string | `body` | Selector scrolled into view when changing page. |
| `container:class` | string | `null` | Classes for the scrolling container (e.g. max-h-80). |
| `bleed` | boolean | `false` | Rows to the card edge; uses --ui-bleed (default 1.5rem). |
| `slots` | bulk · footer | `—` | Selection action bar and footer. |

### `x-ui::table.columns`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `sticky` | boolean | `false` | Header pinned to the top when scrolling. |

### `x-ui::table.column`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `sortable · sorted · direction` | boolean · boolean · asc\|desc | `false · false · asc` | Sorting and aria-sort. |
| `align` | start \| center \| end | `start` | Alignment. |
| `sticky` | boolean \| end | `false` | Column pinned when scrolling. |

### `x-ui::table.row`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `key` | string | `null` | Identifies the row (selection, expansion and wire:key). |
| `sticky` | boolean | `false` | Row pinned below the header when scrolling. |
| `slot detail` | slot | `—` | Content of the expanded row. |

### `x-ui::table.cell`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `align · sticky` | — | `start · false` | Like the column. |
| `variant` | default \| strong | `default` | strong highlights the text (amount, total). |
| `primary` | boolean | `false` | Emphasis; card title in stack. |
| `label` | string | `null` | Column name on the stack card. |

### `others`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `table.group` | heading · count · color · expanded | `—` | Group of rows that opens and closes. |
| `table.empty` | icon · heading · text | `—` | Empty state row. |
| `table.skeleton` | rows · columns | `4 · 3` | Loading rows. |

## Accessibility

- Native table elements (table, thead, th scope="col", tbody), read correctly by screen readers.
- Sortable columns have aria-sort and a focusable button.
- The header checkbox shows the partial state (indeterminate); each checkbox has a label.
- The expandable row chevron exposes aria-expanded; groups do too.
