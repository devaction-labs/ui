{{-- List and detail layout: searchable inbox on the left and the opened item on the right — see references/layouts.md --}}
@php
    $threads = [
        ['title' => 'Contract renewal', 'from' => 'Paula Ramos', 'active' => true],
        ['title' => 'Invoice #1042 disputed', 'from' => 'Tomás Lima', 'active' => false],
        ['title' => 'Kickoff notes', 'from' => 'Ana Souza', 'active' => false],
    ];
@endphp

<x-ui::layout.split list-width="340px">
    <x-slot:sidebar>
        <x-ui::sidebar>
            <x-slot:brand><x-ui::sidebar.brand name="Acme" href="#" /></x-slot:brand>
            <x-ui::sidebar.nav>
                <x-ui::sidebar.item icon="mail-01" href="#" current>Inbox</x-ui::sidebar.item>
                <x-ui::sidebar.item icon="folder-01" href="#">Projects</x-ui::sidebar.item>
            </x-ui::sidebar.nav>
            <x-ui::sidebar.spacer />
            <x-ui::sidebar.profile name="Alex" description="alex@acme.com" />
        </x-ui::sidebar>
    </x-slot:sidebar>

    <x-slot:listHeader>
        <x-ui::input icon="search-01" placeholder="Search…" size="sm" />
    </x-slot:listHeader>

    <x-slot:list>
        @foreach ($threads as $thread)
            <a href="#" @if ($thread['active']) aria-current="true" @endif class="flex flex-col gap-0.5 border-b border-line px-4 py-3 {{ $thread['active'] ? 'bg-card' : '' }}">
                <span class="text-sm font-medium text-ink">{{ $thread['title'] }}</span>
                <span class="text-xs text-muted">{{ $thread['from'] }}</span>
            </a>
        @endforeach
    </x-slot:list>

    <div class="flex flex-col gap-4 p-6">
        <x-ui::page-header heading="Contract renewal" description="From Paula Ramos" />
        <p class="text-sm text-muted">The renewal terms are attached. Please confirm the new monthly seats before Friday.</p>
        <div class="flex gap-2">
            <x-ui::button variant="primary" icon="checkmark-circle-01">Approve</x-ui::button>
            <x-ui::button>Reply</x-ui::button>
        </div>
    </div>
</x-ui::layout.split>
