{{--
    Campo de assinatura posicionado em % da página.

    <x-ui::pdf-viewer.field x="54" y="80" w="38" h="10" label="Assinar" signer="Alex · DevAction Labs" mine wire:click="openSignature" />

    signed: verde, já assinado · mine: âmbar tracejado com "Você assina aqui"
--}}
@props([
    'x' => 0,
    'y' => 0,
    'w' => 30,
    'h' => 10,
    'label' => null,
    'signer' => null,
    'signed' => false,
    'mine' => false,
])

@php
    $label ??= __('Sign');
@endphp

<button
    type="button"
    data-pdf-field
    @if ($mine && ! $signed) data-mine @endif
    style="left: {{ (float) $x }}%; top: {{ (float) $y }}%; width: {{ (float) $w }}%; height: {{ (float) $h }}%"
    x-on:click="$dispatch('pdf-field', { signer: @js($signer), signed: @js((bool) $signed), mine: @js((bool) $mine) })"
    {{ $attributes->class([
        'absolute flex cursor-pointer flex-col items-center justify-center gap-0.5 rounded-md border-[1.5px] font-sans transition focus-visible:ring-2 focus-visible:ring-ring',
        'border-solid border-ok-line bg-ok-bg' => $signed,
        'border-dashed border-ai bg-warn-bg hover:bg-warn-bg/70' => $mine && ! $signed,
        'border-dashed border-line-hover bg-subtle' => ! $signed && ! $mine,
    ]) }}
>
    @if ($mine && ! $signed)
        <span class="absolute -top-[10px] -left-px flex h-[18px] items-center rounded-[5px_5px_5px_0] bg-ai px-1.5 text-[10px] font-semibold text-white uppercase">{{ __('You sign here') }}</span>
    @endif
    <span @class(['text-[0.85em] font-semibold', 'text-ok-fg' => $signed, 'text-warn-fg' => $mine && ! $signed, 'text-ink-3' => ! $signed && ! $mine])>{{ $label }}</span>
    @if ($signer)
        <span class="text-[0.78em] text-muted">{{ $signer }}</span>
    @endif
</button>
