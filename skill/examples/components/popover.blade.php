{{-- Popover: floating panel opened from a button for free-form content, such as milestone details — see references/popover.md --}}
@php
    $milestone = [
        'name' => 'M3 · Finance',
        'status' => 'At risk',
        'due' => 'Fri Oct 16, 6 PM',
        'scope' => '22 of 32 items',
        'prs' => '#142, #145',
    ];
@endphp

{{-- Main: milestone details opened from a button --}}
<x-ui::dropdown>
    <x-ui::button>
        <span class="size-2 rounded-full bg-missing"></span>
        {{ $milestone['name'] }}
    </x-ui::button>

    <x-ui::popover arrow role="dialog" aria-label="Milestone details">
        <div class="flex flex-col gap-2.5">
            <div class="flex items-center justify-between">
                <strong class="font-semibold text-ink">{{ $milestone['name'] }}</strong>
                <x-ui::tag color="risk" size="sm">{{ $milestone['status'] }}</x-ui::tag>
            </div>
            <dl class="grid grid-cols-[auto_1fr] gap-x-3.5 gap-y-1.5 text-[13px]">
                <dt class="text-muted">Due</dt><dd class="text-ink">{{ $milestone['due'] }}</dd>
                <dt class="text-muted">Scope</dt><dd class="text-ink">{{ $milestone['scope'] }}</dd>
                <dt class="text-muted">PRs</dt><dd class="text-ink">{{ $milestone['prs'] }}</dd>
            </dl>
            <div class="flex gap-2">
                <x-ui::button variant="primary" size="sm" href="#">Open milestone</x-ui::button>
                <x-ui::button size="sm">Change due date</x-ui::button>
            </div>
        </div>
    </x-ui::popover>
</x-ui::dropdown>

{{-- Variation: position and align move the panel; here it opens upward, aligned to the end --}}
<div class="flex flex-wrap items-center gap-3 py-24">
    <x-ui::dropdown position="top" align="end">
        <x-ui::button size="sm">Open upward</x-ui::button>

        <x-ui::popover arrow class="w-56">
            <p class="text-sm text-ink">Opens upward, aligned to the end of the button.</p>
        </x-ui::popover>
    </x-ui::dropdown>
</div>
