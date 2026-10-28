{{-- Sortable list: reorderable milestones with codes and due dates, plus a variation without move buttons — see references/sortable-list.md --}}
@php
    $milestones = [
        ['m1', 'M1', 'Member registry', 'delivered'],
        ['m2', 'M2', 'Tithes and offerings', 'delivered'],
        ['m3', 'M3', 'Financial reports', 'Oct 16'],
        ['m4', 'M4', 'Schedules and rosters', 'Nov 20'],
        ['m5', 'M5', 'Members app', 'Dec 18'],
    ];
@endphp

<x-ui::sortable-list label="SGE milestones" class="w-full max-w-lg">
    @foreach ($milestones as [$key, $code, $name, $due])
        <x-ui::sortable-list.item :key="$key" :label="$name">
            <span class="w-[26px] font-mono text-xs font-normal text-muted">{{ $code }}</span>{{ $name }}
            <x-slot:meta>{{ $due }}</x-slot:meta>
        </x-ui::sortable-list.item>
    @endforeach
</x-ui::sortable-list>

{{-- Variation: drag only, no move buttons, Livewire sort handler --}}
<x-ui::sortable-list sort="moveMilestone" label="Milestones" :buttons="false" class="mt-8 w-full max-w-lg">
    <x-ui::sortable-list.item key="7" wire:key="milestone-7">Financial reports</x-ui::sortable-list.item>
    <x-ui::sortable-list.item key="8" wire:key="milestone-8">Schedules and rosters</x-ui::sortable-list.item>
</x-ui::sortable-list>
