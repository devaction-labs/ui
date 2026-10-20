# Skeleton · `<x-ui::skeleton>`

Holds the place of content while it loads, in the same shape. The shimmer stops with prefers-reduced-motion.

## Examples

### Animation

No animation by default. shimmer runs a glow across; pulse blinks slowly.

```blade
<div class="flex w-full max-w-md flex-col gap-3">
    <x-ui::skeleton class="h-10 w-full" />
    <x-ui::skeleton animate="shimmer" class="h-10 w-full" />
    <x-ui::skeleton animate="pulse" class="h-10 w-full" />
</div>
```

### List row

skeleton.group passes the animation down. label tells screen readers what is loading.

```blade
<x-ui::skeleton.group animate="shimmer" label="Loading pull requests" class="flex w-full max-w-md flex-col gap-3">
    @foreach (['w-3/5', 'w-4/5', 'w-1/2'] as $width)
        <div class="flex items-center gap-3">
            <x-ui::skeleton class="size-9 shrink-0 rounded-full" />
            <div class="flex-1">
                <x-ui::skeleton.line class="{{ $width }}" />
                <x-ui::skeleton.line class="w-2/5" />
            </div>
            <x-ui::skeleton class="h-[22px] w-16" />
        </div>
    @endforeach
</x-ui::skeleton.group>
```

### KPI and milestone card

The shape comes from classes: build the skeleton with the same structure as the real content.

```blade
<x-ui::skeleton.group animate="shimmer" class="grid w-full max-w-xl gap-3 sm:grid-cols-2">
    <div class="flex flex-col gap-2 rounded-[14px] border border-line p-3.5">
        <x-ui::skeleton class="h-2.5 w-1/2 rounded-[4px]" />
        <x-ui::skeleton class="h-[34px] w-2/5 rounded-lg" />
    </div>
    <div class="flex items-center gap-3 rounded-[14px] border border-line p-3.5">
        <x-ui::skeleton class="size-11 shrink-0 rounded-full" />
        <div class="flex-1">
            <x-ui::skeleton.line class="w-3/5" />
            <x-ui::skeleton.line class="w-1/3" />
        </div>
    </div>
</x-ui::skeleton.group>
```

### Paragraph

Consecutive lines are spaced like text. size="lg" for headings.

```blade
<div class="w-full max-w-md">
    <x-ui::skeleton.line size="lg" class="w-2/3" />
    <x-ui::skeleton.line />
    <x-ui::skeleton.line />
    <x-ui::skeleton.line class="w-4/5" />
</div>
```

## API

With Livewire, show the skeleton with wire:loading or inside the placeholder of lazy components.

### `x-ui::skeleton`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `animate` | shimmer \| pulse | `null` | Inherited from skeleton.group. |
| `class` | string | `—` | Shape: size, width and corners (rounded-md by default). |

### `x-ui::skeleton.line`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `size` | base \| lg | `base` | 12 or 16 px line. |
| `animate` | shimmer \| pulse | `null` | Inherited from skeleton.group. |

### `x-ui::skeleton.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `animate` | shimmer \| pulse | `null` | Passed to every skeleton inside. |
| `label` | string | `null` | Text for screen readers; becomes role="status". |

## Accessibility

- Each skeleton is aria-hidden: screen readers do not read empty blocks.
- With label, the group becomes role="status" with aria-busy and announces what is loading.
- shimmer and pulse stop with prefers-reduced-motion.
- The kit does not embed text: the "Loading…" comes from you, in your app language.
