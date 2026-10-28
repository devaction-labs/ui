---
kind: component
group: Overlay
components: [tooltip]
---
# Tooltip · `<x-ui::tooltip>`

Names icon-only buttons and shows the shortcut when there is one. Appears on hover after 400 ms and immediately on keyboard focus; Esc closes it.

## Examples

### Toolbar

The tooltip shortcut on the button is the shortest way. For icon-only buttons, the tooltip text also becomes the aria-label. tooltip:kbd shows the shortcut.

```blade
<div role="toolbar" aria-label="PR actions" class="flex gap-1">
    <x-ui::button icon="text-bold" tooltip="Bold" tooltip:kbd="⌘B" />
    <x-ui::button icon="bubble-chat-add" tooltip="Comment" tooltip:kbd="C" />
    <x-ui::button icon="user-add-01" tooltip="Assign" tooltip:kbd="A" />
    <x-ui::button icon="archive-02" tooltip="Archive" tooltip:kbd="E" />
    <x-ui::button icon="delete-02" tooltip="Delete" />
</div>
```

### Positions

top is the default. right in the sidebar; bottom for truncated text and to explain a disabled button.

```blade
<div class="grid grid-cols-2 gap-x-16 gap-y-12 py-6">
    <x-ui::tooltip content="Default">
        <x-ui::button size="sm">Top</x-ui::button>
    </x-ui::tooltip>
    <x-ui::tooltip content="Sidebar" position="right">
        <x-ui::button size="sm">Right</x-ui::button>
    </x-ui::tooltip>
    <x-ui::tooltip content="Campo Limpo Baptist Church" position="bottom">
        <span tabindex="0" class="max-w-[120px] truncate rounded-[9px] border border-dashed border-line-hover px-3 py-2 text-[13px] text-ink-3">Campo Limpo Baptist Church</span>
    </x-ui::tooltip>
    <x-ui::tooltip content="Waiting for client sign-off" position="bottom">
        <x-ui::button size="sm" disabled>Invoice M3</x-ui::button>
    </x-ui::tooltip>
</div>
```

### Rich content

The content slot accepts a title and short text, like the explanation of a metric.

```blade
<x-ui::tooltip position="bottom">
    <x-ui::badge color="warn" icon="information-circle" tabindex="0">RingScore 69</x-ui::badge>
    <x-slot:content>
        <strong>RingScore 69</strong>
        <span class="opacity-80">18 of 26 M3 items ready. Counts items with a PR approved by the AI and by a reviewer.</span>
    </x-slot:content>
</x-ui::tooltip>
```

### On avatars and touch

tooltip on an avatar uses its name. toggleable opens on click, for people on mobile.

```blade
<x-ui::avatar.group>
    <x-ui::avatar name="Alex Nogueira" color="indigo" size="sm" circle tooltip />
    <x-ui::avatar name="Carlos André" color="emerald" size="sm" circle tooltip />
    <x-ui::avatar name="Fernanda Paiva" color="red" size="sm" circle tooltip />
</x-ui::avatar.group>

<x-ui::tooltip content="Available on the Agency plan" toggleable>
    <x-ui::button size="sm" icon="information-circle">Why locked?</x-ui::button>
</x-ui::tooltip>
```

## API

A tooltip never holds essential information: it does not show on mobile. Use toggleable when the explanation must reach touch users.

### `x-ui::tooltip`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `content` | string \| slot | `null` | Text; the slot builds a rich tooltip with title and text. |
| `position` | top \| right \| bottom \| left | `top` | Tooltip side. |
| `align` | center \| start \| end | `center` | Alignment on the chosen side. |
| `gap` | int | `6` | Distance in px to the element. |
| `kbd` | string | `null` | Shortcut at the end of the tooltip. |
| `toggleable` | boolean | `false` | Opens and closes on click. |
| `disabled` | boolean | `false` | Does not open. |
| `delay` | int | `400` | Wait in ms before opening on hover. |

### `x-ui::button · x-ui::avatar`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `tooltip` | string | `null` | Wraps the component in a tooltip (on avatar, true uses the name). |
| `tooltip:position` | top \| right \| bottom \| left | `top` | Tooltip side. |
| `tooltip:kbd` | string | `null` | Shortcut (button only). |

## Accessibility

- role="tooltip" and aria-describedby on the first focusable element inside.
- Opens on keyboard focus and closes with Esc or when leaving the element.
- An icon-only button gets the tooltip text as aria-label when it has none.
- Elements that cannot receive focus need tabindex="0" so the keyboard can reach the tooltip.
