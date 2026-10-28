{{-- Button: primary and outline actions, plus an icon-only button with aria-label — see references/button.md --}}
@php
    $actions = [
        ['label' => 'Request review', 'icon' => 'sent'],
        ['label' => 'Cancel', 'icon' => null],
    ];
@endphp

<div class="flex flex-col gap-4 rounded-lg border border-line bg-card p-4 text-ink">
    <div class="flex flex-wrap items-center gap-2">
        @foreach ($actions as $action)
            <x-ui::button
                variant="{{ $loop->first ? 'primary' : 'outline' }}"
                :icon="$action['icon']"
                wire:click="submit"
            >{{ $action['label'] }}</x-ui::button>
        @endforeach
    </div>

    <p class="text-sm text-muted">Icon-only variation: the aria-label is required.</p>

    <div class="flex items-center gap-2">
        <x-ui::button variant="ghost" size="sm" icon="settings-01" aria-label="Settings" />
        <x-ui::button variant="primary" href="#" icon:trailing="arrow-right-01">Read the docs</x-ui::button>
    </div>
</div>
