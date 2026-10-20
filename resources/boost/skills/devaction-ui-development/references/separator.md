# Separator · `<x-ui::separator>`

A line that separates blocks: plain, with text in the middle or vertical between items on a row.

## Examples

### Horizontal and with text

The plain line divides sections; with text, it separates alternative paths, like on a login screen.

```blade
<div class="flex w-full max-w-sm flex-col gap-5">
    <x-ui::separator />
    <x-ui::separator text="or continue with" />
    <x-ui::separator variant="subtle" />
</div>
```

### Vertical

Between metadata on the same row. It is 14 px tall and centers itself.

```blade
<span class="flex items-center gap-2.5 text-[13px] text-ink-2">
    Today <x-ui::separator vertical /> 3 PRs <x-ui::separator vertical /> 12 h logged
</span>
```

## API

### `x-ui::separator`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `text` | string | `null` | Text in the middle of the line. |
| `vertical` | boolean | `false` | Vertical line between items. |
| `variant` | string | `default` | default · subtle. |

## Accessibility

- role="separator" (with aria-orientation="vertical" when vertical).
- The middle text is read normally; do not put essential information only there.
