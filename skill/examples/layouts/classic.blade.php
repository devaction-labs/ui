{{-- Classic layout: sidebar with nav groups, breadcrumb header and page header with actions — see references/layouts.md --}}
@php
    $projects = [
        ['name' => 'Checkout redesign', 'status' => 'In progress'],
        ['name' => 'Invoice export', 'status' => 'In review'],
        ['name' => 'Onboarding emails', 'status' => 'Done'],
    ];
@endphp

<x-ui::layout.classic>
    <x-slot:sidebar>
        <x-ui::sidebar>
            <x-slot:brand><x-ui::sidebar.brand name="Acme" href="#" /></x-slot:brand>
            <x-ui::sidebar.search />
            <x-ui::sidebar.nav>
                <x-ui::sidebar.item icon="home-01" href="#" current>Today</x-ui::sidebar.item>
            </x-ui::sidebar.nav>
            <x-ui::sidebar.group heading="Work" expandable>
                <x-ui::sidebar.item icon="folder-01" href="#" badge="3">Projects</x-ui::sidebar.item>
                <x-ui::sidebar.item icon="invoice-01" href="#">Invoices</x-ui::sidebar.item>
            </x-ui::sidebar.group>
            <x-ui::sidebar.spacer />
            <x-ui::sidebar.profile name="Alex" description="alex@acme.com" />
        </x-ui::sidebar>
    </x-slot:sidebar>

    <x-slot:header>
        <x-ui::breadcrumbs size="sm">
            <x-ui::breadcrumbs.item href="#">Work</x-ui::breadcrumbs.item>
            <x-ui::breadcrumbs.item>Projects</x-ui::breadcrumbs.item>
        </x-ui::breadcrumbs>
    </x-slot:header>
    <x-slot:actions>
        <x-ui::button size="sm" variant="ghost" icon="notification-01" aria-label="Notifications" />
    </x-slot:actions>

    <x-ui::page-header heading="Projects" description="{{ count($projects) }} active projects">
        <x-slot:actions>
            <x-ui::button variant="primary" icon="folder-add">New project</x-ui::button>
        </x-slot:actions>
    </x-ui::page-header>

    <div class="flex flex-col gap-2">
        @foreach ($projects as $project)
            <div class="flex items-center justify-between gap-3 rounded-xl border border-line bg-card px-4 py-3">
                <span class="text-sm font-medium text-ink">{{ $project['name'] }}</span>
                <x-ui::tag>{{ $project['status'] }}</x-ui::tag>
            </div>
        @endforeach
    </div>
</x-ui::layout.classic>
