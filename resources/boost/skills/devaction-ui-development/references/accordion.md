# Accordion · `<x-ui::accordion>`

Opens and closes sections of content. For FAQs, contract milestones and grouped lists.

## Examples

### Default

Dividers between items and the chevron on the right. exclusive keeps one item open at a time.

```blade
<x-ui::accordion exclusive transition class="w-full max-w-xl">
    <x-ui::accordion.item heading="Does the AI keep the code from my repositories?" expanded>
        No. It reads the PR diff, reviews it and discards it. Only the comments and generated lessons are stored.
    </x-ui::accordion.item>
    <x-ui::accordion.item heading="Who sees reviews before they go to GitHub?">
        Anyone with the reviewer role on the project. In draft mode, nothing is published without approval.
    </x-ui::accordion.item>
    <x-ui::accordion.item heading="Can I turn reviews on for only some repositories?">
        Yes. In Integrations › GitHub, turn on the “Watch” switch for each repository.
    </x-ui::accordion.item>
    <x-ui::accordion.item heading="Reviewing private client PRs" disabled>
        Available once the client authorizes the GitHub App in their organization.
    </x-ui::accordion.item>
</x-ui::accordion>
```

### Cards with status

variant="cards" separates each item. The trailing slot puts tags and actions next to the heading, outside the button.

```blade
<x-ui::accordion variant="cards" class="w-full max-w-xl">
    <x-ui::accordion.item expanded>
        <x-ui::accordion.heading>
            <span class="flex flex-col">
                <span class="font-semibold">M3 · Finance</span>
                <span class="text-[13px] font-normal text-muted">due Fri, Oct 16 · 2 open PRs</span>
            </span>
            <x-slot:trailing>
                <x-ui::tag color="risk">At risk</x-ui::tag>
                <x-ui::button variant="ghost" size="sm" icon="more-vertical" aria-label="More actions for M3" />
            </x-slot:trailing>
        </x-ui::accordion.heading>
        <x-ui::accordion.content>
            Donation entries, cash reconciliation and the treasurer report.
        </x-ui::accordion.content>
    </x-ui::accordion.item>
    <x-ui::accordion.item heading="M4 · Reports and pastoral dashboard">
        18 items planned for November.
    </x-ui::accordion.item>
</x-ui::accordion>
```

### Group

variant="group" puts the items in a bordered box. Good for short lists inside cards.

```blade
<x-ui::accordion variant="group" exclusive class="w-full max-w-xl">
    <x-ui::accordion.item expanded>
        <x-ui::accordion.heading>
            M3 scope <x-ui::badge color="warn" size="sm" class="ml-auto">4 items</x-ui::badge>
        </x-ui::accordion.heading>
        <x-ui::accordion.content>Items 3.1 to 3.4, with two PRs still in review.</x-ui::accordion.content>
    </x-ui::accordion.item>
    <x-ui::accordion.item>
        <x-ui::accordion.heading>
            Installments <x-ui::badge size="sm" class="ml-auto">3</x-ui::badge>
        </x-ui::accordion.heading>
        <x-ui::accordion.content>Down payment, M3 delivery and final acceptance.</x-ui::accordion.content>
    </x-ui::accordion.item>
</x-ui::accordion>
```

### Nested with counts

variant="reverse" puts the chevron on the left. When nesting, pass the variant to the inner accordion too.

```blade
<x-ui::accordion variant="reverse" class="w-full max-w-xl">
    <x-ui::accordion.item expanded>
        <x-ui::accordion.heading>
            Elixir / Phoenix <x-ui::badge size="sm" class="ml-auto font-mono">2</x-ui::badge>
        </x-ui::accordion.heading>
        <x-ui::accordion.content class="border-l border-line pl-3">
            <x-ui::accordion variant="reverse">
                <x-ui::accordion.item heading="Ecto" expanded>Preload vs. join, Ecto.Multi and nested changesets.</x-ui::accordion.item>
                <x-ui::accordion.item heading="LiveView">Streams and uploads. Came from PR #145.</x-ui::accordion.item>
            </x-ui::accordion>
        </x-ui::accordion.content>
    </x-ui::accordion.item>
    <x-ui::accordion.item heading="Laravel / Livewire">
        N+1 queries in Livewire components. Came from ayram-web.
    </x-ui::accordion.item>
</x-ui::accordion>
```

## API

Content of closed items is still found by the browser’s Ctrl+F, which opens the item automatically.

### `x-ui::accordion`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | default \| reverse \| group \| cards | `default` | reverse puts the chevron before the title. |
| `exclusive` | boolean | `false` | Only one item open at a time. |
| `transition` | boolean | `false` | Animates opening and closing with x-collapse. |

### `x-ui::accordion.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Shortcut for accordion.heading; the slot becomes the content. |
| `expanded` | boolean | `false` | Starts open. |
| `disabled` | boolean | `false` | Cannot be opened or closed. |
| `trailing` | slot | `null` | Tags and actions next to the heading. |

### `x-ui::accordion.heading`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Heading text; accepts badges and counts. |
| `trailing` | slot | `null` | Sits outside the button, so it can hold other buttons. |

### `x-ui::accordion.content`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Content shown when the item opens. |

## Accessibility

- Each heading is a <button> inside an <h3>, with aria-expanded and aria-controls.
- The content has role="region" linked to the heading by aria-labelledby.
- ↑ ↓, Home and End move focus between headings of the same accordion.
- Disabled items leave keyboard navigation; the chevron respects motion-reduce.
