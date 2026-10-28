{{-- Panel: card container with heading, description, actions and footer — see references/panel.md --}}
{{-- Main usage: heading, description, actions slot and footer slot. Variation: flush body for lists. --}}

@php
    $repos = ['sge-api', 'hub-api', 'ayram-web'];
    $milestones = [
        ['name' => 'M2 · Calendar and events', 'status' => 'On track', 'color' => 'ok'],
        ['name' => 'M3 · Finance', 'status' => 'At risk', 'color' => 'risk'],
    ];
@endphp

{{-- Main usage --}}
<x-ui::panel heading="Watched repositories" description="The AI reviews every PR opened in these repositories." class="w-full max-w-xl">
    <x-slot:actions>
        <x-ui::button size="sm" icon="add-01" href="#">Add</x-ui::button>
    </x-slot:actions>

    {{ implode(', ', $repos) }}. Dependabot PRs are left out.

    <x-slot:footer>
        <x-ui::button size="sm" variant="ghost" href="#">Cancel</x-ui::button>
        <x-ui::button size="sm" variant="primary" wire:click="save">Save</x-ui::button>
    </x-slot:footer>
</x-ui::panel>

{{-- Variation: card parts built by hand, with a flush list body --}}
<x-ui::card size="sm" class="mt-6 w-full max-w-xl">
    <x-ui::card.header>
        <x-ui::card.heading size="sm">Milestones</x-ui::card.heading>
        <x-ui::card.subheading mono>SGE · Church Management</x-ui::card.subheading>
        <x-ui::card.actions>
            <x-ui::button size="sm" href="#">View all</x-ui::button>
        </x-ui::card.actions>
    </x-ui::card.header>
    <x-ui::card.body flush>
        <ul class="divide-y divide-line-soft border-y border-line-soft">
            @foreach ($milestones as $milestone)
                <li class="flex items-center justify-between px-[22px] py-3">
                    {{ $milestone['name'] }}
                    <x-ui::badge :color="$milestone['color']" size="sm" dot>{{ $milestone['status'] }}</x-ui::badge>
                </li>
            @endforeach
        </ul>
    </x-ui::card.body>
</x-ui::card>
