---
kind: layout
group: Layouts
components: [layout.centered, layout.classic, layout.docs, layout.split, layout.topbar, layout.topbar.item, logo, main, spacer]
---
# Layouts · `<x-ui::layout.*>`

Six page shells. All of them use the kit's sidebar, header, page header and content area, so switching themes doesn't mean rewriting screens.

| Theme | Component | Use for |
| --- | --- | --- |
| Classic (default) | `x-ui::layout.classic` | Dashboards, internal systems, SaaS |
| Rail | `x-ui::layout.classic collapsed` | Dense screens: diffs, kanban (⌘B toggles) |
| Top bar | `x-ui::layout.topbar` | Client portals, sites with login (up to 6 sections) |
| List and detail | `x-ui::layout.split` | Inbox, reviews, tablet |
| Centered | `x-ui::layout.centered` | Settings, onboarding, checkout (720px column) |
| Docs | `x-ui::layout.docs` | Docs, knowledge base, changelog |

## Classic

```blade
<x-ui::layout.classic>
    <x-slot:sidebar>
        <x-ui::sidebar>
            <x-slot:brand><x-ui::sidebar.brand name="Acme" href="/" /></x-slot:brand>
            <x-ui::sidebar.search />
            <x-ui::sidebar.nav>
                <x-ui::sidebar.item icon="home-01" href="/" current>Today</x-ui::sidebar.item>
            </x-ui::sidebar.nav>
            <x-ui::sidebar.group heading="Work" expandable>
                <x-ui::sidebar.item icon="folder-01" href="/projects" badge="8">Projects</x-ui::sidebar.item>
            </x-ui::sidebar.group>
            <x-ui::sidebar.spacer />
            <x-ui::sidebar.profile name="Alex" description="alex@acme.com" />
        </x-ui::sidebar>
    </x-slot:sidebar>

    <x-slot:header>
        <x-ui::breadcrumbs size="sm">
            <x-ui::breadcrumbs.item href="/work">Work</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.item>Projects</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot:header>
    <x-slot:actions><x-ui::button size="sm" variant="ghost" icon="notification-01" aria-label="Notifications" /></x-slot:actions>

    <x-ui::page-header heading="Projects" description="6 active projects">
        <x-slot:actions><x-ui::button variant="primary" icon="folder-add">New project</x-ui::button></x-slot:actions>
    </x-ui::page-header>
    …
</x-ui::layout.classic>
```

The sidebar's open/collapsed state lives in `$store.uiSidebar` (`toggle()`, `collapsed`, `open`) and is remembered in the browser.

## Top bar

```blade
<x-ui::layout.topbar width="1280px">
    <x-slot:brand><x-ui::logo size="xs">Client portal</x-ui::logo></x-slot:brand>
    <x-slot:nav>
        <x-ui::layout.topbar.item href="/projects" icon="folder-01" current>Projects</x-ui::layout.topbar.item>
        <x-ui::layout.topbar.item href="/invoices" icon="invoice-01">Invoices</x-ui::layout.topbar.item>
    </x-slot:nav>
    <x-slot:actions><x-ui::avatar name="Paula" size="sm" circle /></x-slot:actions>
    …
</x-ui::layout.topbar>
```

## List and detail

```blade
<x-ui::layout.split list-width="340px">
    <x-slot:sidebar>…</x-slot:sidebar>
    <x-slot:listHeader><x-ui::input icon="search-01" placeholder="Search…" size="sm" /></x-slot:listHeader>
    <x-slot:list>…items…</x-slot:list>
    …opened item…
</x-ui::layout.split>
```

## Centered and docs

```blade
<x-ui::layout.centered width="720px">
    <x-slot:header><x-ui::logo size="xs">Acme</x-ui::logo></x-slot:header>
    <x-ui::page-header heading="Settings" />
</x-ui::layout.centered>

<x-ui::layout.docs>
    <x-slot:header>…</x-slot:header>
    <x-slot:sidebar><a href="/docs/start" aria-current="page">Getting started</a></x-slot:sidebar>
    <x-slot:toc><a href="#install">Install</a></x-slot:toc>
    <h1>Getting started</h1>
</x-ui::layout.docs>
```

## Layout pieces

Small components the shells and the sidebar share, also usable on their own.

```blade
<x-ui::main container>…</x-ui::main>   {{-- white rounded panel beside the sidebar; `container` centers with a max width --}}
<x-ui::logo size="xs">Docs</x-ui::logo>   {{-- DevAction symbol; size: xs · sm (default) · md · lg --}}
<div class="flex"><x-ui::spacer /><x-ui::button>Save</x-ui::button></div>   {{-- pushes siblings to the edges --}}
```
