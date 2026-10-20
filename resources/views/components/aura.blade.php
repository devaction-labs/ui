{{--
    Aura: borda de luz que gira em volta de algo enquanto a IA trabalha (inspirada no Aura do daisyUI).

    <x-ui::aura><x-ui::card>…</x-ui::card></x-ui::aura>
    <x-ui::aura variant="dual" size="lg" radius="18px">…</x-ui::aura>
    <x-ui::aura variant="glow" color="risk">…</x-ui::aura>        pulsa sem girar: precisa de você
    <x-ui::aura :active="$isThinking">…</x-ui::aura>              sem active, vira uma borda comum

    variant: spin (padrão) · dual (âmbar e azul) · glow (pulsa)
    color:   ai (padrão) · info · ok · risk · ink
    size:    xs (1 px) · sm (1,5 px, padrão) · lg (2,5 px)
    radius:  raio do conteúdo (padrão 14px); a aura soma a espessura
    O conteúdo precisa de fundo próprio (card, toast, botão): a luz aparece só na borda.
--}}
@props([
    'variant' => 'spin',
    'color' => 'ai',
    'size' => 'sm',
    'radius' => '14px',
    'active' => true,
])

@php
    $hex = match ($color) {
        'info' => '#2563EB',
        'ok' => '#16A34A',
        'risk' => '#C62828',
        'ink' => '#18181B',
        default => '#D29A1E',
    };
    $thickness = match ($size) {
        'xs' => '1px',
        'lg' => '2.5px',
        default => '1.5px',
    };
    $gradient = $variant === 'dual'
        ? "conic-gradient(from 0deg, transparent 0 10%, {$hex} 22%, transparent 34% 60%, #2563EB 72%, transparent 84%)"
        : "conic-gradient(from 0deg, transparent 0 62%, {$hex} 80%, transparent 92%)";
@endphp

@if ($variant === 'glow')
    <div
        data-aura
        data-variant="glow"
        {{ $attributes->class(['motion-safe:animate-aura-glow' => $active]) }}
        style="--aura-color: {{ $hex }}8C; border-radius: {{ $radius }}"
    >
        {{ $slot }}
    </div>
@else
    <div
        data-aura
        data-variant="{{ $variant }}"
        {{ $attributes->class(['relative isolate overflow-hidden bg-line']) }}
        style="padding: {{ $thickness }}; border-radius: calc({{ $radius }} + {{ $thickness }})"
    >
        @if ($active)
            <span
                aria-hidden="true"
                class="pointer-events-none absolute top-1/2 left-1/2 -z-10 aspect-square w-[max(200%,300px)] -translate-x-1/2 -translate-y-1/2 motion-safe:animate-aura-spin"
                style="background: {{ $gradient }}"
            ></span>
        @endif
        <div class="relative" style="border-radius: {{ $radius }}">
            {{ $slot }}
        </div>
    </div>
@endif
