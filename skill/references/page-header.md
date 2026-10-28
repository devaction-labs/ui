---
kind: component
group: Navigation
components: [page-header]
---
# Page header · `<x-ui::page-header>`

Top of an app page: breadcrumb, status, heading with a context line, actions and tabs, always in the same order.

## Examples

### Project page

The four parts of the design: 1 breadcrumb, 2 status, 32 px heading and context, 3 actions (at most one primary), 4 tabs.

```blade
<x-ui::page-header heading="SGE · Church Management" description="Grace Community Church · M3 due Friday" class="w-full">
    <x-slot:breadcrumbs>
        <x-ui::breadcrumbs.item href="#">Projects</x-ui::breadcrumbs.item>
        <x-ui::breadcrumbs.item>SGE</x-ui::breadcrumbs.item>
    </x-slot:breadcrumbs>
    <x-slot:status>
        <x-ui::tag color="ok" size="sm">Active</x-ui::tag>
    </x-slot:status>
    <x-slot:actions>
        <x-ui::button icon="download-04">Export</x-ui::button>
        <x-ui::button variant="primary" icon="add-01">New task</x-ui::button>
    </x-slot:actions>
    <x-slot:tabs>
        <x-ui::tabs>
            <x-ui::tab name="overview" selected>Overview</x-ui::tab>
            <x-ui::tab name="tasks" badge="12">Tasks</x-ui::tab>
            <x-ui::tab name="prs">PRs</x-ui::tab>
            <x-ui::tab name="contract">Contract</x-ui::tab>
        </x-ui::tabs>
    </x-slot:tabs>
</x-ui::page-header>
```

### Simple

Just heading and action. size="sm" for inner pages and drawers.

```blade
<x-ui::page-header heading="Contacts" description="1,284 people in 312 companies" size="sm" class="w-full">
    <x-slot:actions>
        <x-ui::button variant="primary" icon="add-01">New contact</x-ui::button>
    </x-slot:actions>
</x-ui::page-header>
```

## API

The breadcrumbs slot takes x-ui::breadcrumbs.item directly; the default slot goes below the description (metadata, avatars).

### `x-ui::page-header`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Page heading (h1). |
| `description` | string | `null` | Context line. |
| `size` | base \| sm | `base` | Heading of 32 or 24 px. |
| `slots` | breadcrumbs · status · actions · tabs | `—` | The optional parts of the header. |

## Accessibility

- The heading is the page h1; use one per page.
- The breadcrumb comes before the heading in reading order, as on screen.
- Actions have text, not just an icon; the primary one is the only filled button.
