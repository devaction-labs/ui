# Aura · `<x-ui::aura>`

A light border that spins around a card, toast or button while the AI works. The glow version pulses without spinning, for things that need you.

## Examples

### AI at work

The default spins in amber; dual mixes amber and blue. The content needs its own background, because the light only shows on the border.

```blade
<div class="grid w-full max-w-md gap-6">
    <x-ui::aura>
        <div role="status" class="flex items-center gap-3 rounded-[14px] bg-card px-4 py-3.5">
            <x-ui::spinner class="text-ai" />
            <span class="flex flex-1 flex-col text-sm"><strong class="font-semibold text-ink">Reviewing PR #214</strong><span class="text-muted">12 of 31 files</span></span>
        </div>
    </x-ui::aura>
    <x-ui::aura variant="dual" size="lg">
        <div class="flex items-center gap-3 rounded-[14px] bg-card px-4 py-3.5">
            <span class="flex size-[34px] items-center justify-center rounded-[10px] bg-info-bg text-info-fg"><x-ui::icon name="book-02" class="size-[18px]" /></span>
            <span class="flex flex-1 flex-col text-sm"><strong class="font-semibold text-ink">Writing a new lesson</strong><span class="text-muted">Ecto.Multi and rollback</span></span>
        </div>
    </x-ui::aura>
</div>
```

### Glow, sizes and off

variant="glow" pulses in the chosen color. size changes the thickness. With :active="false" the aura becomes a plain border, without animation.

```blade
<div class="flex w-full flex-wrap items-center gap-5">
    <x-ui::aura variant="glow" color="risk">
        <div role="alert" class="flex items-center gap-3 rounded-[14px] bg-card px-4 py-3.5 text-sm">
            <strong class="font-semibold text-ink">PR #145 is out of scope</strong>
            <x-ui::button size="sm" variant="primary">Draft amendment</x-ui::button>
        </div>
    </x-ui::aura>
    <x-ui::aura size="xs" radius="12px"><x-ui::button class="rounded-xl">Button · xs</x-ui::button></x-ui::aura>
    <x-ui::aura size="lg" radius="12px"><x-ui::button class="rounded-xl">Button · lg</x-ui::button></x-ui::aura>
    <x-ui::aura :active="false" radius="12px"><x-ui::button class="rounded-xl">Still</x-ui::button></x-ui::aura>
</div>
```

## API

Tie the aura to server state: :active="$isReviewing". It respects prefers-reduced-motion (stays still).

### `x-ui::aura`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `variant` | spin \| dual \| glow | `spin` | Spins, spins in two colors, or pulses. |
| `color` | ai \| info \| ok \| risk \| ink | `ai` | Light color. |
| `size` | xs \| sm \| lg | `sm` | Thickness of 1, 1.5 or 2.5 px. |
| `radius` | string | `14px` | Content radius. |
| `active` | boolean | `true` | Turns the animation on and off. |

## Accessibility

- The light is decorative (aria-hidden); say what is happening in text, with role="status".
- With prefers-reduced-motion, the aura neither spins nor pulses.
- Use glow only for things that need action: pulsing draws a lot of attention.
