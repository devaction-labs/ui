{{-- Tooltip: names icon-only buttons with shortcuts, and positions a tooltip on a disabled button — see references/tooltip.md --}}
@php
    $actions = [
        ['icon' => 'text-bold', 'label' => 'Bold', 'kbd' => '⌘B'],
        ['icon' => 'bubble-chat-add', 'label' => 'Comment', 'kbd' => 'C'],
        ['icon' => 'user-add-01', 'label' => 'Assign', 'kbd' => 'A'],
        ['icon' => 'archive-02', 'label' => 'Archive', 'kbd' => 'E'],
        ['icon' => 'delete-02', 'label' => 'Delete', 'kbd' => null],
    ];
@endphp

<div class="flex w-full max-w-xl flex-col gap-6 rounded-xl border border-line bg-card p-4">
    {{-- Main usage: icon-only toolbar, the tooltip text also becomes the aria-label --}}
    <div role="toolbar" aria-label="Actions" class="flex gap-1">
        @foreach ($actions as $action)
            <x-ui::button
                :icon="$action['icon']"
                :tooltip="$action['label']"
                :tooltip:kbd="$action['kbd']"
                :aria-label="$action['label']"
                wire:click="run('{{ strtolower($action['label']) }}')"
            />
        @endforeach
    </div>

    {{-- Variation: bottom position explains a disabled button --}}
    <div class="flex items-center gap-3">
        <x-ui::tooltip content="Waiting for client sign-off" position="bottom">
            <x-ui::button size="sm" disabled>Invoice M3</x-ui::button>
        </x-ui::tooltip>
        <span class="text-[13px] text-muted">Hover or focus the button to see why it is disabled.</span>
    </div>
</div>
