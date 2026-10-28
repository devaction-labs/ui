---
kind: component
group: Navigation
components: [breadcrumbs, breadcrumbs.item]
---
# Breadcrumb · `<x-ui::breadcrumb>`

Shows where the page sits and leads back to the levels above. The last item is the current page.

## Examples

### Default

The design’s "/" separator. Items with href become links; the last one, without href, gets aria-current="page".

```blade
<x-ui::breadcrumbs>
    <x-ui::breadcrumbs.item href="#">Today</x-ui::breadcrumbs.item>
    <x-ui::breadcrumbs.item href="#">SGE</x-ui::breadcrumbs.item>
    <x-ui::breadcrumbs.item>PR #142</x-ui::breadcrumbs.item>
</x-ui::breadcrumbs>
```

### With icon, chevron and hidden levels

separator="chevron" swaps the slash for a chevron; size="sm" for the top of the page. An icon-only item needs an aria-label.

```blade
<x-ui::breadcrumbs separator="chevron" size="sm">
    <x-ui::breadcrumbs.item href="#" icon="home-01" aria-label="Home" />
    <x-ui::breadcrumbs.item icon="more-horizontal" aria-label="More levels" />
    <x-ui::breadcrumbs.item href="#">Contracts</x-ui::breadcrumbs.item>
    <x-ui::breadcrumbs.item>Amendment 02</x-ui::breadcrumbs.item>
</x-ui::breadcrumbs>
```

## API

Item attributes (wire:navigate, target, aria-label) go to the link or the text.

### `x-ui::breadcrumbs`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `separator` | slash \| chevron | `slash` | Separator between items. |
| `size` | sm \| base | `base` | Text of 13 or 14 px. |

### `x-ui::breadcrumbs.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `href` | string | `null` | Link for the level; without it, it is the current page. |
| `icon` | string | `null` | Icon before the text (or alone, with aria-label). |
| `current` | boolean | `no href and has text` | Forces or removes aria-current="page". |

## Accessibility

- A nav with an accessible label ("Breadcrumb", translated with the app locale) and an ordered list (ol), as the ARIA pattern requires.
- The current page has aria-current="page".
- Separators are aria-hidden, so screen readers don’t read the slashes.
