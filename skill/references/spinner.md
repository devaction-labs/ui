---
kind: component
group: Base
components: [spinner]
---
# Spinner · `<x-ui::spinner>`

Shows that something is loading when you cannot tell how much is left. For known progress, use Progress.

## Examples

### Ring and dots

ring is the default. dots suits conversations and AI answers in progress.

```blade
<x-ui::spinner label="Loading" />
<x-ui::spinner variant="dots" label="Loading" />
```

### Sizes

xs and sm fit inside buttons and table rows; lg in empty areas that are loading.

```blade
<x-ui::spinner size="xs" />
<x-ui::spinner size="sm" />
<x-ui::spinner />
<x-ui::spinner size="lg" />
<x-ui::spinner variant="dots" size="lg" />
```

### Colors

ai in amber for AI work. current inherits the text color, like inside badges and buttons.

```blade
<span class="inline-flex items-center gap-2 text-sm text-indigo-fg">
    <x-ui::spinner variant="dots" size="sm" color="current" />
    AI reviewing…
</span>
<x-ui::spinner color="ai" label="The AI is reviewing" />
<x-ui::badge color="warn"><x-ui::spinner size="xs" color="current" />Syncing</x-ui::badge>
```

### In a button

Button already uses the spinner: with wire:click it appears on its own during the request.

```blade
<x-ui::button variant="primary" loading>Publishing</x-ui::button>
<x-ui::button size="sm" loading>Saving</x-ui::button>
```

## API

### `x-ui::spinner`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | ring \| dots | `ring` | Spinning ring or three blinking dots. |
| `size` | xs \| sm \| base \| lg | `base` | 14, 16, 22 and 32 px. |
| `color` | default \| current \| ai | `default` | current uses the surrounding text color. |
| `label` | string | `null` | Text for screen readers; becomes role="status". |

## Accessibility

- With label, the spinner becomes role="status" and the text is announced.
- Without label it is decorative (aria-hidden): use it when the text next to it already says what is loading.
- With prefers-reduced-motion the ring spins very slowly and the dots stop.
- The kit does not embed text: the "Loading" comes from you, in your app language.
