{{-- Properties: side panel of a task with sections for people, labels, board fields and milestone progress — see references/properties.md --}}
@php
    $assignees = [
        ['name' => 'Carlos André', 'color' => 'cyan'],
        ['name' => 'Alex Nogueira', 'color' => 'violet'],
    ];
@endphp

<x-ui::properties class="w-[300px]">
    <x-ui::properties.section heading="Assignees">
        <x-slot:action><x-ui::button size="xs" variant="ghost" icon="settings-02" aria-label="Edit assignees" /></x-slot:action>
        @foreach ($assignees as $person)
            <span class="flex items-center gap-2"><x-ui::avatar :name="$person['name']" :color="$person['color']" size="xs" circle />{{ $person['name'] }}</span>
        @endforeach
    </x-ui::properties.section>
    <x-ui::properties.section heading="Labels">
        <div class="flex flex-wrap gap-1.5">
            <x-ui::badge color="info" size="sm">backend</x-ui::badge>
            <x-ui::badge color="risk" size="sm">risk: money</x-ui::badge>
        </div>
    </x-ui::properties.section>
    <x-ui::properties.section heading="Board">
        <x-ui::properties.item label="Column">In progress</x-ui::properties.item>
        <x-ui::properties.item label="Due">Thu 15 · tomorrow</x-ui::properties.item>
        <x-ui::properties.item label="Estimate" mono>5 pts</x-ui::properties.item>
    </x-ui::properties.section>
    <x-ui::properties.section heading="Milestone">
        <x-ui::progress value="62" color="green" label="Delivery 3 · Finance" show-value />
    </x-ui::properties.section>
</x-ui::properties>

{{-- Variation: card with a stacked automation rule --}}
<x-ui::properties variant="card" class="mt-8 w-[320px]">
    <x-ui::properties.section heading="Details">
        <x-ui::properties.item label="Reporter">Fernando Pinto</x-ui::properties.item>
        <x-ui::properties.item label="Sprint">Sprint 3.4</x-ui::properties.item>
        <x-ui::properties.item label="Story points" mono>5</x-ui::properties.item>
        <x-ui::properties.item label="Priority"><x-ui::badge color="risk" size="sm">High</x-ui::badge></x-ui::properties.item>
    </x-ui::properties.section>
    <x-ui::properties.section heading="Automation">
        <x-ui::properties.item label="When the linked PR is merged" stacked>Move to Done and notify the reporter</x-ui::properties.item>
    </x-ui::properties.section>
</x-ui::properties>
