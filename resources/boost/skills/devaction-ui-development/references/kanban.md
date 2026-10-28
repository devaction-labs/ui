---
kind: component
group: Data
components: [kanban, kanban.add, kanban.card, kanban.column]
---
# Kanban · `<x-ui::kanban>`

Board of columns with cards: tasks, pipeline deals, candidates. Dragging between columns uses Livewire 4's wire:sort.

## Examples

### Project tasks

limit shows the work in progress and turns red when it hits the limit. done strikes the card through. In a Livewire component, sort="moveTask" makes the cards draggable.

```blade
<x-ui::kanban class="w-full">
    <x-ui::kanban.column id="todo" heading="To do" count="2" color="#A1A1AA">
        <x-ui::kanban.card key="1" due="Fri 16" assignee="Ana Souza">
            Monthly treasurer report
            <x-slot:tags><x-ui::tag size="sm" color="info">SGE</x-ui::tag></x-slot:tags>
        </x-ui::kanban.card>
        <x-ui::kanban.card key="2" due="Oct 23" assignee="Bruno Lima">Checkout with Pix</x-ui::kanban.card>
        <x-slot:footer><x-ui::kanban.add>New task</x-ui::kanban.add></x-slot:footer>
    </x-ui::kanban.column>
    <x-ui::kanban.column id="doing" heading="In progress" count="2" color="#2563EB" limit="3">
        <x-ui::kanban.card key="3" due="yesterday" overdue assignee="Carla Dias">
            Fix N+1 in contributions
            <x-slot:tags>
                <x-ui::tag size="sm" color="risk">Bug</x-ui::tag>
                <x-ui::tag size="sm" :uppercase="false">#142</x-ui::tag>
            </x-slot:tags>
        </x-ui::kanban.card>
        <x-ui::kanban.card key="4" due="today" assignee="Ana Souza">Cash reconciliation</x-ui::kanban.card>
        <x-slot:footer><x-ui::kanban.add>New task</x-ui::kanban.add></x-slot:footer>
    </x-ui::kanban.column>
    <x-ui::kanban.column id="review" heading="In review" count="2" color="#D29A1E" limit="2">
        <x-ui::kanban.card key="5" assignee="Bruno Lima">
            Receipt uploads
            <x-slot:tags><x-ui::tag size="sm" color="warn" icon="sparkles">AI reviewed</x-ui::tag></x-slot:tags>
        </x-ui::kanban.card>
        <x-ui::kanban.card key="6" assignee="Carla Dias">Member sign-up</x-ui::kanban.card>
    </x-ui::kanban.column>
    <x-ui::kanban.column id="done" heading="Done" count="1" color="#16A34A">
        <x-ui::kanban.card key="7" done assignee="Ana Souza">Sign in with Google</x-ui::kanban.card>
    </x-ui::kanban.column>
</x-ui::kanban>
```

## API

With sort, Livewire calls the method with (card key, new position, target column id); you save the order in the database. Buttons inside a draggable card need wire:sort:ignore.

### `x-ui::kanban.column`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `id` | string | `null` | Identifies the column (sent as the 3rd sort parameter). |
| `heading · count · color` | string | `—` | Heading, count and dot color. |
| `limit` | int | `null` | Work-in-progress limit. |
| `sort · group` | string | `null · kanban` | wire:sort method and the group of columns that exchange cards. |
| `count-text` | string | `null` | wire:text expression for the count (e.g. $wire.totals.review.count): the number and the limit update instantly, without a server round trip. |
| `slots` | footer · actions | `—` | Footer (add) and header actions. |

### `x-ui::kanban.card`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `key` | string | `null` | Identifies the card (wire:sort:item and wire:key). |
| `due · overdue` | string · boolean | `null · false` | Due date, red when overdue. |
| `assignee` | string | `null` | Assignee name (avatar with tooltip). |
| `done · href` | boolean · string | `false · null` | Struck-through card; card as a link. |
| `slot tags` | slot | `—` | Tags below the title. |

### `x-ui::kanban.add`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `slot` | string | `Add` | Button text. |

## Accessibility

- Each column is a section with its name as aria-label and an h3 heading.
- Dragging should not be the only way to move: also offer a "Move to" menu on the card.
- The limit is shown as text ("2/2 limit"), not only by color.
