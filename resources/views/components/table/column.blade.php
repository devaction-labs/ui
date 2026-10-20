{{--
    Coluna do cabeçalho. Ordenável: passe sorted, direction e o wire:click que ordena.

    <x-ui::table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Nome</x-ui::table.column>
    <x-ui::table.column align="end">Horas</x-ui::table.column>

    align:  start · center · end
    sticky: presa à esquerda ao rolar para o lado (sticky="end" prende à direita)
--}}
@aware([
    'variant' => 'default',
    'density' => 'default',
])

@props([
    'sortable' => false,
    'sorted' => false,
    'direction' => 'asc',
    'align' => 'start',
    'sticky' => false,
])

@php
    $ariaSort = $sortable ? ($sorted ? ($direction === 'desc' ? 'descending' : 'ascending') : 'none') : null;
    $stickySide = $sticky === 'end' ? 'end' : ($sticky ? 'start' : null);
@endphp

<th
    scope="col"
    @if ($ariaSort) aria-sort="{{ $ariaSort }}" @endif
    {{ $attributes->class([
        'border-b border-line px-3.5 font-medium whitespace-nowrap',
        'h-10 text-[13px]' => $density !== 'compact',
        'h-[34px] text-xs' => $density === 'compact',
        'text-muted' => ! $sorted,
        'text-ink' => $sorted,
        'text-left' => $align === 'start',
        'text-center' => $align === 'center',
        'text-right' => $align === 'end',
        'cursor-pointer select-none hover:text-ink' => $sortable,
        'sticky left-0 z-10 shadow-[inset_-1px_0_0_var(--line)]' => $stickySide === 'start',
        'sticky right-0 z-10 shadow-[inset_1px_0_0_var(--line)]' => $stickySide === 'end',
        'bg-subtle' => $stickySide && $variant !== 'minimal',
        'bg-card' => $stickySide && $variant === 'minimal',
    ]) }}
>
    @if ($sortable)
        <button type="button" @class([
            'inline-flex cursor-pointer items-center gap-1 outline-none focus-visible:underline',
            'flex-row-reverse' => $align === 'end',
        ])>
            {{ $slot }}
            <span aria-hidden="true" @class(['text-xs', 'opacity-30' => ! $sorted])>{{ $sorted && $direction === 'desc' ? '↓' : '↑' }}</span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
