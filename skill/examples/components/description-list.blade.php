{{-- Description list: label and value pairs for detail pages — see references/description-list.md --}}
@php
    $project = [
        ['label' => 'Client', 'value' => 'Grace Community Church'],
        ['label' => 'Model', 'value' => 'Milestone-based scope'],
        ['label' => 'Current milestone', 'value' => 'M3 · Financial reports'],
        ['label' => 'Repository', 'value' => 'devaction-labs/sge-api', 'mono' => true, 'copyable' => true],
        ['label' => 'Owner', 'value' => 'Fernando Pinto'],
    ];

    $contact = [
        ['label' => 'Email', 'value' => 'ana@acme.com'],
        ['label' => 'Phone', 'value' => '+1 (555) 012-3456', 'mono' => true],
        ['label' => 'Company', 'value' => 'Acme Inc.'],
        ['label' => 'Role', 'value' => 'Head of Sales'],
    ];
@endphp

{{-- Main usage: label beside value, one column --}}
<x-ui::description-list class="w-full max-w-md">
    @foreach ($project as $row)
        <x-ui::description-list.item
            :label="$row['label']"
            :mono="$row['mono'] ?? false"
            :copyable="$row['copyable'] ?? false"
        >{{ $row['value'] }}</x-ui::description-list.item>
    @endforeach
</x-ui::description-list>

{{-- Variation: two columns, label on top --}}
<x-ui::description-list columns="2" class="mt-6 w-full max-w-xl">
    @foreach ($contact as $row)
        <x-ui::description-list.item
            :label="$row['label']"
            :mono="$row['mono'] ?? false"
        >{{ $row['value'] }}</x-ui::description-list.item>
    @endforeach
</x-ui::description-list>
