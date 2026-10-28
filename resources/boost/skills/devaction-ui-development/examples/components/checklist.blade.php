{{-- Checklist: titled list with progress and per-item owners, plus a compact variation — see references/checklist.md --}}
@php
    $items = [
        ['label' => 'Post and settle in the same transaction', 'owner' => 'Carlos', 'checked' => true],
        ['label' => 'Roll back if the receipt fails', 'owner' => 'Carlos', 'checked' => true],
        ['label' => 'Generate the receipt after the commit', 'owner' => 'Carlos', 'checked' => false],
        ['label' => 'Test a failure halfway through', 'owner' => 'Alex', 'checked' => false],
    ];
@endphp

<x-ui::checklist heading="Ready to merge" class="w-full max-w-md">
    @foreach ($items as $item)
        <x-ui::checklist.item :checked="$item['checked']" :description="$item['owner']">{{ $item['label'] }}</x-ui::checklist.item>
    @endforeach
    <x-slot:footer>
        <x-ui::button size="sm" variant="ghost" icon="add-01">Add an item</x-ui::button>
    </x-slot:footer>
</x-ui::checklist>

{{-- Variation: short checklist without heading --}}
<x-ui::checklist class="mt-8 w-full max-w-sm">
    <x-ui::checklist.item checked>Lock the cash row while posting</x-ui::checklist.item>
    <x-ui::checklist.item>Send the receipt by email</x-ui::checklist.item>
</x-ui::checklist>
