# Timeline · `<x-ui::timeline>`

Shows a sequence of events or steps: the history of a PR, the progress of a milestone, the stages of a process.

## Examples

### PR events

An empty indicator becomes a colored dot. Gray is neutral, amber is the AI, red is "someone is missing", blue is learning.

```blade
<x-ui::timeline class="w-full max-w-md">
    <x-ui::timeline.item>
        <x-ui::timeline.indicator />
        <x-ui::timeline.content heading="PR #142 opened" meta="carlos-andre · 09:12" />
    </x-ui::timeline.item>
    <x-ui::timeline.item>
        <x-ui::timeline.indicator color="amber" />
        <x-ui::timeline.content heading="AI reviewed: 2 issues" meta="09:14" />
    </x-ui::timeline.item>
    <x-ui::timeline.item>
        <x-ui::timeline.indicator color="red" />
        <x-ui::timeline.content heading="Alex requested changes" meta="09:40" />
    </x-ui::timeline.item>
    <x-ui::timeline.item>
        <x-ui::timeline.indicator color="blue" />
        <x-ui::timeline.content heading="Lesson sent to the author" meta="09:41" />
    </x-ui::timeline.item>
</x-ui::timeline>
```

### With icons and a block

With content, the indicator becomes a circle. timeline.block takes the full width, for callouts and comments.

```blade
<x-ui::timeline class="w-full max-w-md">
    <x-ui::timeline.item>
        <x-ui::timeline.indicator><x-ui::icon name="git-pull-request" class="size-3.5" /></x-ui::timeline.indicator>
        <x-ui::timeline.content heading="carlos-andre opened PR #142" meta="4 days ago" />
    </x-ui::timeline.item>
    <x-ui::timeline.item>
        <x-ui::timeline.indicator color="amber"><x-ui::icon name="sparkles" class="size-3.5" /></x-ui::timeline.indicator>
        <x-ui::timeline.content heading="The AI reviewed the code" meta="4 days ago" />
    </x-ui::timeline.item>
    <x-ui::timeline.item>
        <x-ui::timeline.block>
            <x-ui::callout variant="ai" icon="sparkles" heading="Wrap the entry in a database transaction" text="If the second write fails, the cash account is left without the credit." />
        </x-ui::timeline.block>
    </x-ui::timeline.item>
    <x-ui::timeline.item>
        <x-ui::timeline.indicator color="green"><x-ui::icon name="tick-02" class="size-3.5" /></x-ui::timeline.indicator>
        <x-ui::timeline.content heading="Alex approved the changes" meta="3 days ago" />
    </x-ui::timeline.item>
</x-ui::timeline>
```

### Steps with status

status on the item paints the indicator and connector: complete in ink, current with a ring, incomplete neutral. size="lg" for numbers.

```blade
<x-ui::timeline size="lg" class="w-full max-w-md">
    <x-ui::timeline.item status="complete">
        <x-ui::timeline.indicator>1</x-ui::timeline.indicator>
        <x-ui::timeline.content heading="Connect GitHub" meta="authorized" />
    </x-ui::timeline.item>
    <x-ui::timeline.item status="current">
        <x-ui::timeline.indicator>2</x-ui::timeline.indicator>
        <x-ui::timeline.content heading="Choose repositories" meta="6 found" />
    </x-ui::timeline.item>
    <x-ui::timeline.item status="incomplete">
        <x-ui::timeline.indicator>3</x-ui::timeline.indicator>
        <x-ui::timeline.content heading="First review" meta="the AI runs right after" />
    </x-ui::timeline.item>
</x-ui::timeline>
```

### Horizontal

horizontal places the steps side by side, with the connector between the indicators.

```blade
<x-ui::timeline horizontal class="max-w-xl">
    <x-ui::timeline.item status="complete">
        <x-ui::timeline.indicator><x-ui::icon name="contracts" class="size-3.5" /></x-ui::timeline.indicator>
        <x-ui::timeline.content heading="Contract" meta="signed" />
    </x-ui::timeline.item>
    <x-ui::timeline.item status="current">
        <x-ui::timeline.indicator><x-ui::icon name="source-code" class="size-3.5" /></x-ui::timeline.indicator>
        <x-ui::timeline.content heading="Development" meta="M3 of 5" />
    </x-ui::timeline.item>
    <x-ui::timeline.item status="incomplete">
        <x-ui::timeline.indicator><x-ui::icon name="rocket-01" class="size-3.5" /></x-ui::timeline.indicator>
        <x-ui::timeline.content heading="Delivery" meta="expected Nov" />
    </x-ui::timeline.item>
</x-ui::timeline>
```

## API

### `x-ui::timeline`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `horizontal` | boolean | `false` | Steps side by side. |
| `size` | base \| lg | `base` | 24 or 32 px indicators. |

### `x-ui::timeline.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `status` | complete \| current \| incomplete | `null` | Paints the indicator and the connector to the next item. |

### `x-ui::timeline.indicator`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `color` | red \| amber \| green \| blue \| indigo \| zinc | `null` | Colored background of the dot or circle. |
| `status` | complete \| current \| incomplete | `from the item` | Overrides the status on the indicator only. |
| `variant` | bare | `null` | No background or fixed size, for large icons. |

### `x-ui::timeline.content`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Event title. |
| `meta` | string | `null` | Author, time or context in gray. |

### `x-ui::timeline.block`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Full-width content, no indicator. |

## Accessibility

- Renders <ol> and <li>: screen readers announce the order and total of events.
- Connectors are decorative (aria-hidden).
- The dot color is never the only cue: the heading says what happened.
- Icons inside the indicator are hidden; the event text carries the information.
