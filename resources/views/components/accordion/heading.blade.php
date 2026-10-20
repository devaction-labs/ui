{{--
    Cabeçalho do item: um <button> dentro de <h3>, com aria-expanded e aria-controls.

    <x-ui::accordion.heading>Elixir / Phoenix</x-ui::accordion.heading>
    <x-ui::accordion.heading>
        M3 · Financeiro
        <x-slot:trailing>
            <x-ui::tag color="risk">Em risco</x-ui::tag>
            <x-ui::button variant="ghost" size="sm" icon="more-vertical" aria-label="Mais ações" />
        </x-slot:trailing>
    </x-ui::accordion.heading>
--}}
@aware([
    'variant' => 'default',
])

@props([
    'trailing' => null,
    'expanded' => false,
    'disabled' => false,
])

@php
    $iconOnLeft = $variant !== 'default';

    $rowClasses = match ($variant) {
        'group' => 'px-3.5',
        'cards' => 'pr-3 pl-3.5 transition-colors group-data-[open=true]/item:bg-subtle',
        default => 'px-0.5',
    };

    $triggerClasses = match ($variant) {
        'group' => 'min-h-[50px] gap-2.5',
        'cards' => 'min-h-15 gap-3',
        'reverse' => 'min-h-12 gap-2.5',
        default => 'min-h-14 gap-3',
    };
@endphp

<div {{ $attributes->class(['flex items-center gap-3', $rowClasses]) }}>
    <h3 class="m-0 min-w-0 flex-1 text-[15px] font-medium">
        <button
            type="button"
            data-accordion-trigger
            x-bind:id="$id('accordion-trigger', itemId)"
            x-bind:aria-controls="$id('accordion-panel', itemId)"
            aria-expanded="{{ $expanded ? 'true' : 'false' }}"
            x-bind:aria-expanded="open.toString()"
            x-on:click="toggle()"
            @disabled($disabled)
            @class([
                'group/trigger flex w-full cursor-pointer items-center text-left text-ink outline-none',
                'rounded-md focus-visible:ring-2 focus-visible:ring-ring',
                'disabled:cursor-not-allowed disabled:text-muted',
                $triggerClasses,
            ])
        >
            @if ($iconOnLeft)
                <x-ui::icon name="arrow-right-01" stroke-width="1.8" class="size-4 text-muted transition-transform duration-200 group-aria-expanded/trigger:rotate-90 motion-reduce:transition-none" />
            @endif

            <span class="flex min-w-0 flex-1 items-center gap-2.5">{{ $slot }}</span>

            @unless ($iconOnLeft)
                <x-ui::icon name="arrow-down-01" stroke-width="1.8" class="size-4.5 text-muted transition-transform duration-200 group-aria-expanded/trigger:rotate-180 motion-reduce:transition-none" />
            @endunless
        </button>
    </h3>

    @if ($trailing)
        <div class="flex shrink-0 items-center gap-2">{{ $trailing }}</div>
    @endif
</div>
