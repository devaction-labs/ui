# Button · `<x-ui::button>`

Triggers an action or navigates. Variants, sizes, loading state and an icon-only version.

## Examples

### Variants

primary for the main action on the screen, outline for the rest, ghost in toolbars and danger for things that can’t be undone.

```blade
<x-ui::button variant="primary">Request review</x-ui::button>
<x-ui::button>Cancel</x-ui::button>
<x-ui::button variant="ghost">View details</x-ui::button>
<x-ui::button variant="danger">Delete project</x-ui::button>
```

### More variants

filled and subtle for dense areas; ai for everything the AI does; info for tracks and tips.

```blade
<x-ui::button variant="filled">Filter</x-ui::button>
<x-ui::button variant="subtle">Clear</x-ui::button>
<x-ui::button variant="ai" icon="sparkles">Review with AI</x-ui::button>
<x-ui::button variant="info">Start track</x-ui::button>
```

### Sizes

base is the default. lg on authentication screens and in the app; sm and xs in tables and dense cards.

```blade
<x-ui::button variant="primary" size="xs">Tiny</x-ui::button>
<x-ui::button variant="primary" size="sm">Small</x-ui::button>
<x-ui::button variant="primary">Medium</x-ui::button>
<x-ui::button variant="primary" size="lg">Large</x-ui::button>
```

### With icon and icon only

icon goes before the text and icon:trailing after it. With only an icon the button becomes square and requires an aria-label.

```blade
<x-ui::button icon="github">Connect GitHub</x-ui::button>
<x-ui::button variant="primary" icon:trailing="arrow-right-01">Continue</x-ui::button>
<x-ui::button icon="more-horizontal" aria-label="More actions" />
<x-ui::button variant="ghost" icon="settings-01" aria-label="Settings" />
```

### Loading and disabled

With wire:click the spinner shows automatically during the request. loading forces the state; disabled blocks it.

```blade
<x-ui::button variant="primary" icon="sent" loading>Publishing</x-ui::button>
<x-ui::button disabled>Disabled</x-ui::button>
```

### As a link

With href the component renders an <a>. disabled on a link becomes aria-disabled and leaves the tab order.

```blade
<x-ui::button href="#" icon:trailing="arrow-right-01">Read the docs</x-ui::button>
<x-ui::button href="#" disabled>Unavailable</x-ui::button>
```

## API

Other HTML attributes (wire:click, type, x-on:click, data-*) go straight to the root element.

### `x-ui::button`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | outline \| primary \| filled \| danger \| ghost \| subtle \| ai \| info | `outline` | Use a single primary per area of the screen. |
| `size` | xs \| sm \| base \| lg | `base` | Height of 24, 32, 38 and 46 px. |
| `icon` | string | `null` | Hugeicons icon name before the text. |
| `icon:trailing` | string | `null` | Icon after the text. |
| `square` | boolean | `auto` | Square button. Automatic when it only has an icon. |
| `inset` | top \| bottom \| left \| right | `null` | Negative margin to align ghost buttons with text. |
| `loading` | boolean \| null | `null` | Automatic with wire:click. true forces it, false turns it off. |
| `disabled` | boolean | `false` | On a link it becomes aria-disabled. |
| `href` | string | `null` | Renders an <a> instead of a <button>. |

## Accessibility

- Renders a <button> or <a> depending on href, never a clickable div.
- Visible focus with a 2 px ring using the --ring token.
- loading sets aria-busy and keeps the text for screen readers.
- An icon-only button takes the aria-label directly on the component.
