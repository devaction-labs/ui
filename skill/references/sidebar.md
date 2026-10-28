---
kind: component
group: Navigation
components: [sidebar, sidebar.brand, sidebar.group, sidebar.item, sidebar.nav, sidebar.profile, sidebar.search, sidebar.spacer, sidebar.toggle]
---
# Sidebar · `<x-ui::sidebar>`

The app main navigation: 236 px open, 64 px collapsed with icons only and, on mobile, a top bar with the menu in a drawer.

## Examples

### App shell

Sidebar on the frame background and x-ui::main as the white panel. The button next to the brand collapses it; when collapsed, hover the icons to see their names. The choice is saved in the browser.

```blade
<div class="flex h-[620px] w-full flex-col overflow-hidden rounded-[18px] bg-frame md:flex-row md:gap-3.5 md:p-3.5">
    <x-ui::sidebar :sticky="false">
        <x-slot:brand>
            <x-ui::sidebar.brand name="DevAction IA" href="#" />
        </x-slot:brand>
        <x-ui::sidebar.search />
        <x-ui::sidebar.nav>
            <x-ui::sidebar.item icon="home-01" href="#">Today</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="folder-01" href="#" current>Projects</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="file-01" href="#">Contracts</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="git-pull-request" href="#" badge="4">Pull Requests</x-ui::sidebar.item>
        </x-ui::sidebar.nav>
        <x-ui::sidebar.group heading="Account" expandable>
            <x-ui::sidebar.item icon="book-02" href="#">Learning</x-ui::sidebar.item>
            <x-ui::sidebar.item icon="settings-02" href="#">Settings</x-ui::sidebar.item>
        </x-ui::sidebar.group>
        <x-ui::sidebar.spacer />
        <x-ui::sidebar.profile name="Alex Nogueira" description="DevAction Labs" />
    </x-ui::sidebar>
    <x-ui::main>
        <x-ui::page-header heading="SGE · Church Management" description="Campo Limpo Baptist Church · M3 due Friday" size="sm" />
    </x-ui::main>
</div>
```

## API

In a real layout keep sticky (the default): the sidebar stays on screen while the content scrolls. To open or collapse it from elsewhere, call $store.uiSidebar.toggle(). Built-in labels (search, collapse button) follow the app locale.

### `x-ui::sidebar`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `label` | string | `Main` | Navigation aria-label. |
| `collapsible` | boolean | `true` | Collapse button next to the brand. |
| `sticky` | boolean | `true` | Stays on screen on desktop. |
| `slots` | brand · mobile | `—` | Brand (on top and in the mobile bar) and extra actions in the mobile bar. |

### `x-ui::sidebar.item`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `icon · href` | string | `null` | Icon and target; without href it becomes a button. |
| `current` | boolean | `false` | Current page. |
| `badge` | string | `null` | Counter; badge:color risk · plain · ai. |

### `x-ui::sidebar.group`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `heading` | string | `null` | Group title in monospace. |
| `expandable · expanded` | boolean | `false · true` | Group that opens and closes. |

### `others`

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `sidebar.brand` | name · href | `—` | Logo and name; the slot replaces the logo. |
| `sidebar.search` | placeholder · kbd | `Search · ⌘K` | Search button. |
| `sidebar.profile` | name · description · avatar | `—` | Signed-in person; the slot becomes a menu. |
| `sidebar.toggle · sidebar.spacer · main` | — | `—` | Open/collapse button, flexible space and main panel. |

## Accessibility

- nav with an aria-label; the current item has aria-current="page".
- When collapsed, the item name stays in the link for screen readers and shows in a tooltip on hover and focus.
- On mobile, the menu opens in a drawer with a dimmed backdrop; Esc and the backdrop close it.
- The collapse button says what it does ("Collapse menu", "Expand menu") and exposes aria-expanded.
