{{-- Data table: sortable columns, status badges, row actions, an empty state and a sticky compact variation — see references/data-table.md --}}
@php
    $sortBy = 'date';
    $sortDirection = 'desc';
    $orders = [
        ['id' => 'o1', 'customer' => 'Ana Souza', 'date' => '2026-10-12', 'status' => 'Paid', 'color' => 'ok', 'amount' => '$1,290'],
        ['id' => 'o2', 'customer' => 'Bruno Lima', 'date' => '2026-10-14', 'status' => 'Pending', 'color' => 'warn', 'amount' => '$480'],
        ['id' => 'o3', 'customer' => 'Carla Dias', 'date' => '2026-10-15', 'status' => 'Overdue', 'color' => 'risk', 'amount' => '$2,050'],
    ];
    $hours = [
        ['day' => 1, 'task' => 'Review of PR #201', 'hours' => 2],
        ['day' => 2, 'task' => 'Review of PR #202', 'hours' => 3],
        ['day' => 3, 'task' => 'Review of PR #203', 'hours' => 1],
    ];
@endphp

<x-ui::table class="w-full">
    <x-ui::table.columns>
        <x-ui::table.column>Customer</x-ui::table.column>
        <x-ui::table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">Date</x-ui::table.column>
        <x-ui::table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">Status</x-ui::table.column>
        <x-ui::table.column sortable :sorted="$sortBy === 'amount'" :direction="$sortDirection" wire:click="sort('amount')" align="end">Amount</x-ui::table.column>
        <x-ui::table.column><span class="sr-only">Actions</span></x-ui::table.column>
    </x-ui::table.columns>

    <x-ui::table.rows>
        @forelse ($orders as $order)
            <x-ui::table.row :key="$order['id']">
                <x-ui::table.cell>
                    <span class="flex items-center gap-3"><x-ui::avatar size="xs" :name="$order['customer']" color="auto" circle />{{ $order['customer'] }}</span>
                </x-ui::table.cell>
                <x-ui::table.cell class="whitespace-nowrap">{{ $order['date'] }}</x-ui::table.cell>
                <x-ui::table.cell class="py-0">
                    <x-ui::badge size="sm" :color="$order['color']" dot>{{ $order['status'] }}</x-ui::badge>
                </x-ui::table.cell>
                <x-ui::table.cell variant="strong" align="end">{{ $order['amount'] }}</x-ui::table.cell>
                <x-ui::table.cell class="py-0" align="end">
                    <x-ui::button variant="ghost" size="sm" icon="more-horizontal" aria-label="Order actions" />
                </x-ui::table.cell>
            </x-ui::table.row>
        @empty
            <x-ui::table.empty icon="file-01" heading="No orders match this filter" text="Try another status or create a new order." />
        @endforelse
    </x-ui::table.rows>
</x-ui::table>

{{-- Variation: compact card with a sticky header and a scrollable body --}}
<x-ui::card class="mt-8 w-full max-w-xl">
    <x-ui::card.body>
        <p class="mb-3 text-sm font-semibold">Latest entries</p>
        <x-ui::table bleed container:class="max-h-56">
            <x-ui::table.columns sticky>
                <x-ui::table.column>Description</x-ui::table.column>
                <x-ui::table.column align="end">Hours</x-ui::table.column>
            </x-ui::table.columns>
            <x-ui::table.rows>
                @foreach ($hours as $entry)
                    <x-ui::table.row :key="$entry['day']">
                        <x-ui::table.cell>{{ $entry['task'] }}</x-ui::table.cell>
                        <x-ui::table.cell align="end" variant="strong">{{ $entry['hours'] }} h</x-ui::table.cell>
                    </x-ui::table.row>
                @endforeach
            </x-ui::table.rows>
        </x-ui::table>
    </x-ui::card.body>
</x-ui::card>
