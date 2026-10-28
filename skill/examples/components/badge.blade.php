{{-- Badge: status labels in running text, with optional dot and icon — see references/badge.md --}}
@php
    $rows = [
        ['name' => 'Checkout redesign', 'status' => 'risk', 'label' => 'At risk', 'icon' => null],
        ['name' => 'Invoice export', 'status' => 'ok', 'label' => 'On track', 'icon' => null],
        ['name' => 'Stock sync', 'status' => 'warn', 'label' => 'Reviewed by AI', 'icon' => 'sparkles'],
        ['name' => 'Tax rules review', 'status' => 'info', 'label' => 'Waiting', 'icon' => null],
    ];
@endphp

<div class="flex flex-col gap-4 rounded-xl border border-line bg-card p-4 text-ink">
    {{-- Main usage: colored status with dot --}}
    <div class="flex flex-wrap gap-2">
        <x-ui::badge color="ok" dot>On track</x-ui::badge>
        <x-ui::badge color="warn" dot>Attention</x-ui::badge>
        <x-ui::badge color="risk" dot>At risk</x-ui::badge>
        <x-ui::badge color="info" dot>Waiting</x-ui::badge>
        <x-ui::badge>Closed</x-ui::badge>
    </div>

    {{-- Variation: sm size inside a dense table, with icon before the text --}}
    <table class="w-full text-left text-sm">
        <thead class="text-muted">
            <tr>
                <th class="py-2 font-medium">Item</th>
                <th class="py-2 font-medium">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr class="border-t border-line">
                    <td class="py-2">{{ $row['name'] }}</td>
                    <td class="py-2">
                        <x-ui::badge :color="$row['status']" size="sm" :icon="$row['icon']">
                            {{ $row['label'] }}
                        </x-ui::badge>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
