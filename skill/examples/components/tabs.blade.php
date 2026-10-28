{{-- Tabs: tab group with linked panels, plus segmented tabs with urgent count badges — see references/tabs.md --}}
@php
    $project = [
        ['name' => 'overview', 'label' => 'Overview', 'body' => 'SGE · Church Management, milestone M3 in progress.'],
        ['name' => 'contract', 'label' => 'Contract', 'body' => '5 milestones, 2 installments paid, scope closed.'],
        ['name' => 'prs', 'label' => 'Pull Requests', 'badge' => '6', 'body' => '6 open PRs, 2 with issues flagged by the AI.'],
        ['name' => 'learning', 'label' => 'Learning', 'body' => '3 lessons generated from the team\'s PRs.'],
    ];

    $queue = [
        ['name' => 'month', 'label' => 'Due this month', 'badge' => '4', 'color' => 'red'],
        ['name' => 'review', 'label' => 'In review', 'badge' => '3'],
        ['name' => 'delivered', 'label' => 'Delivered', 'badge' => '2'],
    ];
@endphp

<x-ui::tab.group class="w-full max-w-xl">
    <x-ui::tabs>
        @foreach ($project as $tab)
            <x-ui::tab
                :name="$tab['name']"
                :selected="$loop->first"
                :badge="$tab['badge'] ?? null"
            >{{ $tab['label'] }}</x-ui::tab>
        @endforeach
    </x-ui::tabs>

    @foreach ($project as $tab)
        <x-ui::tab.panel :name="$tab['name']" :selected="$loop->first" class="text-sm text-muted">
            {{ $tab['body'] }}
        </x-ui::tab.panel>
    @endforeach
</x-ui::tab.group>

<x-ui::tabs variant="segmented" class="mt-6">
    @foreach ($queue as $tab)
        <x-ui::tab
            :name="$tab['name']"
            :badge="$tab['badge']"
            :badge:color="$tab['color'] ?? null"
        >{{ $tab['label'] }}</x-ui::tab>
    @endforeach
</x-ui::tabs>
