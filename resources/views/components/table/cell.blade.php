{{--
    Célula da tabela.

    <x-ui::table.cell primary>Igreja Viva</x-ui::table.cell>
    <x-ui::table.cell align="end" label="Horas" class="font-mono">132 h</x-ui::table.cell>

    align:   start · center · end
    primary: texto em destaque; no modo stack vira o título do card
    label:   nome da coluna mostrado no card do modo stack
    sticky:  presa à esquerda (ou "end", à direita) ao rolar para o lado
    variant: default · strong (texto em destaque, como primary sem virar título no stack)
--}}
@aware([
    'variant' => 'default',
    'density' => 'default',
    'stack' => false,
])

@props([
    'align' => 'start',
    'primary' => false,
    'label' => null,
    'sticky' => false,
    'variant' => 'default',
])

@php
    $isStrong = $primary || $variant === 'strong';
    $stickySide = $sticky === 'end' ? 'end' : ($sticky ? 'start' : null);
@endphp

<td
    @if ($label) data-label="{{ $label }}" @endif
    {{ $attributes->class([
        'border-b border-line-soft px-3.5',
        'h-12' => $density !== 'compact',
        'h-9 text-[13px]' => $density === 'compact',
        'font-medium text-ink' => $isStrong,
        'text-ink-2' => ! $isStrong,
        'text-left' => $align === 'start',
        'text-center' => $align === 'center',
        'text-right tabular-nums' => $align === 'end',
        'sticky left-0 z-[1] bg-card shadow-[inset_-1px_0_0_var(--line)]' => $stickySide === 'start',
        'sticky right-0 z-[1] bg-card shadow-[inset_1px_0_0_var(--line)]' => $stickySide === 'end',
        'max-sm:col-span-2 max-sm:h-auto max-sm:border-0 max-sm:p-0 max-sm:pr-8 max-sm:text-left max-sm:text-[15px] max-sm:font-semibold' => $stack && $primary,
        'max-sm:contents max-sm:text-[13px] max-sm:before:text-muted max-sm:before:content-[attr(data-label)]' => $stack && ! $primary && $label,
        'max-sm:absolute max-sm:top-2.5 max-sm:right-2 max-sm:h-auto max-sm:border-0 max-sm:p-0' => $stack && ! $primary && ! $label,
    ]) }}
>
    @if ($stack && ! $primary && $label)
        <span class="max-sm:text-right max-sm:text-[13px]">{{ $slot }}</span>
    @else
        {{ $slot }}
    @endif
</td>
