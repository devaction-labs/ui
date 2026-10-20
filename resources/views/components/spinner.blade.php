{{--
    Indicador de carregamento.

    <x-ui::spinner />
    <x-ui::spinner variant="dots" />
    <x-ui::spinner size="lg" color="ai" label="A IA está revisando" />
    <x-ui::spinner size="xs" color="current" />   dentro de botões e textos coloridos

    variant: ring (padrão) · dots
    size:    xs (14) · sm (16) · base (22, padrão) · lg (32)
    color:   default · current (cor do texto) · ai (âmbar)
    label:   texto para leitores de tela; sem ele o spinner é decorativo
--}}
@props([
    'variant' => 'ring',
    'size' => 'base',
    'color' => 'default',
    'label' => null,
])

@php
    [$ringClasses, $dotClasses, $gapClasses] = match ($size) {
        'xs' => ['size-3.5 border-2', 'size-[5px]', 'gap-[3px]'],
        'sm' => ['size-4 border-2', 'size-1.5', 'gap-[3px]'],
        'lg' => ['size-8 border-[3px]', 'size-[9px]', 'gap-1.5'],
        default => ['size-[22px] border-[3px]', 'size-[7px]', 'gap-1'],
    };

    [$trackClasses, $headClasses, $dotToneClasses] = match ($color) {
        'current' => ['border-current/25', 'border-t-current', 'bg-current'],
        'ai' => ['border-warn-line', 'border-t-ai', 'bg-ai'],
        default => ['border-line', 'border-t-ink', 'bg-ink'],
    };
@endphp

<span
    data-spinner
    @if ($label) role="status" @else aria-hidden="true" @endif
    {{ $attributes->class(['inline-flex shrink-0 items-center', $gapClasses => $variant === 'dots']) }}
>
    @if ($variant === 'dots')
        @foreach (['', '[animation-delay:200ms]', '[animation-delay:400ms]'] as $delayClasses)
            <span class="{{ $dotClasses }} {{ $dotToneClasses }} {{ $delayClasses }} animate-blink rounded-full motion-reduce:animate-none"></span>
        @endforeach
    @else
        <span class="{{ $ringClasses }} {{ $trackClasses }} {{ $headClasses }} block animate-spinner rounded-full motion-reduce:animate-[spin_2.4s_linear_infinite]"></span>
    @endif

    @if ($label)
        <span class="sr-only">{{ $label }}</span>
    @endif
</span>
