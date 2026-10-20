# Panel · `<x-ui::panel>`

The container for the design's panels: a 6 px frame and a card with heading, supporting line, actions and footer. Underneath, it is the Flux-style Card.

## Examples

### Panel

heading, description and the actions slot build the header. The frame is on by default.

```blade
<x-ui::panel heading="Watched repositories" description="The AI reviews every PR opened in these repositories." class="w-full max-w-xl">
    <x-slot:actions>
        <x-ui::button size="sm" icon="add-01">Add</x-ui::button>
    </x-slot:actions>
    sge-api, hub-api and ayram-web. Dependabot PRs are left out.
</x-ui::panel>
```

### Technical line and footer

mono sets the description in Geist Mono, like the artboards. The footer slot holds the final actions.

```blade
<x-ui::panel heading="Switch with label" description="x-ui::switch · label · description" mono class="w-full max-w-xl">
    Changes save instantly, with no save button.
    <x-slot:footer>
        <x-ui::button size="sm" variant="ghost">Cancel</x-ui::button>
        <x-ui::button size="sm" variant="primary">Save</x-ui::button>
    </x-slot:footer>
</x-ui::panel>
```

### Edge-to-edge body

flush removes the body's side padding for tables and lists.

```blade
<x-ui::panel heading="Milestones" description="SGE · Church Management" flush class="w-full max-w-xl">
    <ul class="divide-y divide-line-soft border-y border-line-soft">
        <li class="flex items-center justify-between px-[22px] py-3">M2 · Calendar and events <x-ui::badge color="ok" size="sm" dot>On track</x-ui::badge></li>
        <li class="flex items-center justify-between px-[22px] py-3">M3 · Finance <x-ui::badge color="risk" size="sm" dot>At risk</x-ui::badge></li>
    </ul>
</x-ui::panel>
```

### Card

To build it by hand, use the Card and its parts, like in Flux. No frame by default.

```blade
<x-ui::card size="sm" class="w-full max-w-md">
    <x-ui::card.header>
        <x-ui::card.heading size="sm">Password</x-ui::card.heading>
        <x-ui::card.subheading>Changed 3 months ago</x-ui::card.subheading>
        <x-ui::card.actions>
            <x-ui::button size="sm">Change</x-ui::button>
        </x-ui::card.actions>
    </x-ui::card.header>
    <x-ui::card.body>Use at least 12 characters, with letters, numbers and symbols.</x-ui::card.body>
</x-ui::card>
```

### Sizes and soft variant

size changes padding, corners and the space between parts. variant="soft" removes the border and uses the soft background.

```blade
<div class="grid w-full gap-3 sm:grid-cols-3">
    <x-ui::card size="xs"><x-ui::card.heading size="sm">xs</x-ui::card.heading>Dense lists and sidebars.</x-ui::card>
    <x-ui::card size="sm"><x-ui::card.heading size="sm">sm</x-ui::card.heading>Cards in a grid.</x-ui::card>
    <x-ui::card variant="soft"><x-ui::card.heading size="sm">soft</x-ui::card.heading>No border.</x-ui::card>
</div>
```

## API

Other HTML attributes go to the inner card, not the frame.

### `x-ui::panel`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Panel heading. |
| `description` | string | `null` | Supporting line below the heading. |
| `mono` | boolean | `false` | Description in Geist Mono. |
| `size` | xs \| sm \| md \| lg | `md` | Passed to the card. |
| `wrap` | boolean | `true` | 6 px frame. |
| `flush` | boolean | `false` | Body without side padding. |
| `actions` | slot | `null` | Buttons to the right of the heading. |
| `footer` | slot | `null` | Footer with a divider. |

### `x-ui::card`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `size` | xs \| sm \| md \| lg | `md` | Padding, corners and space between parts. |
| `variant` | default \| soft | `default` | soft: soft background and no border. |
| `wrap` | boolean | `false` | 6 px frame. |

### `x-ui::card.heading`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `size` | sm \| base \| lg | `base` | 15, 18 and 20 px. |
| `level` | int | `2` | Heading level (h2, h3…). |

### `x-ui::card.subheading`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `mono` | boolean | `false` | Technical line in Geist Mono. |

### `x-ui::card.body`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `flush` | boolean | `false` | No side padding. |

### `x-ui::card.header · actions · footer`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `default` | slot | `—` | Card parts: header, actions on the right and footer. |

## Accessibility

- The heading is an <h2> by default; adjust it with level to keep the page hierarchy.
- The frame is visual only: the content stays in reading order.
- Header actions come after the heading in the DOM, so screen readers hear the context first.
- Supporting text keeps a minimum 4.5:1 contrast in both themes.
