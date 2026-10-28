{{-- Dropdown: action menu opened from a button, with single choice, actions and multi-select filters — see references/dropdown.md --}}
@php
    $sortOptions = [
        ['value' => 'due', 'label' => 'By due date', 'checked' => true],
        ['value' => 'amount', 'label' => 'By amount', 'checked' => false],
        ['value' => 'risk', 'label' => 'By risk', 'checked' => false],
    ];
    $statusOptions = [
        ['label' => 'On track', 'checked' => true],
        ['label' => 'At risk', 'checked' => true],
        ['label' => 'Planned', 'checked' => false],
    ];
@endphp

<div class="flex flex-wrap items-center gap-3 rounded-lg border border-line bg-card p-4 text-ink">
    {{-- Main: sort menu with a single choice, a separator and actions --}}
    <x-ui::dropdown>
        <x-ui::button icon:trailing="arrow-down-01">Sort</x-ui::button>

        <x-ui::menu>
            <x-ui::menu.radio.group wire:model.live="sort">
                @foreach ($sortOptions as $option)
                    <x-ui::menu.radio :value="$option['value']" :checked="$option['checked']">{{ $option['label'] }}</x-ui::menu.radio>
                @endforeach
            </x-ui::menu.radio.group>
            <x-ui::menu.separator />
            <x-ui::menu.item icon="download-04" kbd="⌘E" href="#">Export CSV</x-ui::menu.item>
            <x-ui::menu.item icon="archive-02" variant="danger" href="#">Archive milestone</x-ui::menu.item>
        </x-ui::menu>
    </x-ui::dropdown>

    {{-- Variation: filters with checkboxes; keep-open keeps the menu open while picking several --}}
    <x-ui::dropdown>
        <x-ui::button icon="filter" icon:trailing="arrow-down-01">Status</x-ui::button>

        <x-ui::menu keep-open>
            @foreach ($statusOptions as $option)
                <x-ui::menu.checkbox :checked="$option['checked']">{{ $option['label'] }}</x-ui::menu.checkbox>
            @endforeach
            <x-ui::menu.checkbox disabled>Archived</x-ui::menu.checkbox>
        </x-ui::menu>
    </x-ui::dropdown>
</div>
