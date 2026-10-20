{{--
    Tooltip (API no estilo Flux). Aparece no hover (400 ms) e no foco do teclado; Esc fecha.

    <x-ui::tooltip content="Arquivar" kbd="E">
        <x-ui::button icon="archive-02" aria-label="Arquivar" />
    </x-ui::tooltip>

    <x-ui::tooltip position="bottom">
        <x-ui::button disabled>Faturar M3</x-ui::button>
        <x-slot:content>
            <strong>RingScore 69</strong>
            18 de 26 itens do M3 prontos.
        </x-slot:content>
    </x-ui::tooltip>

    position:   top (padrão) · right · bottom · left
    align:      center (padrão) · start · end
    toggleable: abre no clique, para telas de toque
    Nunca guarde informação essencial num tooltip: no celular ele não aparece.
--}}
@props([
    'content' => null,
    'position' => 'top',
    'align' => 'center',
    'gap' => 6,
    'kbd' => null,
    'toggleable' => false,
    'disabled' => false,
    'delay' => 400,
])

@php
    $isRich = $content instanceof \Illuminate\View\ComponentSlot;
    $isVertical = in_array($position, ['top', 'bottom'], true);

    $sideClasses = match ($position) {
        'bottom' => 'top-full mt-(--tooltip-gap)',
        'left' => 'right-full mr-(--tooltip-gap)',
        'right' => 'left-full ml-(--tooltip-gap)',
        default => 'bottom-full mb-(--tooltip-gap)',
    };

    $alignClasses = match (true) {
        $isVertical && $align === 'start' => 'left-0',
        $isVertical && $align === 'end' => 'right-0',
        $isVertical => 'left-1/2 -translate-x-1/2',
        $align === 'start' => 'top-0',
        $align === 'end' => 'bottom-0',
        default => 'top-1/2 -translate-y-1/2',
    };
@endphp

<span
    data-tooltip
    x-data="uiTooltip({ delay: {{ (int) $delay }}, toggleable: @js((bool) $toggleable), disabled: @js((bool) $disabled) })"
    x-id="['ui-tooltip']"
    x-on:mouseenter="schedule()"
    x-on:mouseleave="hide()"
    x-on:focusin="show()"
    x-on:focusout="hide()"
    x-on:keydown.escape.window="hide()"
    @if ($toggleable) x-on:click="toggle()" @endif
    style="--tooltip-gap: {{ (int) $gap }}px"
    {{ $attributes->class(['relative inline-flex']) }}
>
    {{ $slot }}

    <span
        x-ref="tooltip"
        role="tooltip"
        x-bind:id="$id('ui-tooltip')"
        x-show="open"
        x-ui-float="{ placement: @js($position), align: @js($align), gap: {{ (int) $gap }} }"
        x-cloak
        x-transition.opacity.duration.100ms
        @class([
            'pointer-events-none absolute z-50 bg-ink text-xs text-on-ink shadow-[0_6px_16px_rgb(24_24_27/0.2)]',
            $sideClasses,
            $alignClasses,
            'flex items-center gap-2 rounded-lg px-[9px] py-1.5 whitespace-nowrap' => ! $isRich,
            'flex w-max max-w-[280px] flex-col gap-1 rounded-xl px-3.5 py-3 leading-normal [&_strong]:text-[13px] [&_strong]:font-semibold' => $isRich,
        ])
    >
        @if ($isRich)
            {{ $content }}
        @else
            {{ $content }}
            @if ($kbd)
                <kbd class="rounded-[4px] bg-current/15 px-[5px] py-px font-mono text-[10px]">{{ $kbd }}</kbd>
            @endif
        @endif
    </span>
</span>
