{{--
    Linha da tabela. key identifica a linha na seleção e na expansão.

    <x-ui::table.row :key="$contact->id">…células…</x-ui::table.row>
    <x-ui::table.row key="142" expandable>
        …células…
        <x-slot:detail>Achados do PR</x-slot:detail>
    </x-ui::table.row>
--}}
@aware([
    'density' => 'default',
    'selectable' => false,
    'expandable' => false,
    'stack' => false,
])

@props([
    'key' => null,
    'sticky' => false,
])

@php
    $rowKey = (string) ($key ?? '');
    $hasDetail = isset($detail) && $expandable;
    $cellHeight = $density === 'compact' ? 'h-9' : 'h-12';
@endphp

<tr
    data-table-row
    @if ($rowKey !== '') wire:key="table-row-{{ $rowKey }}" @endif
    @if ($selectable && $rowKey !== '') x-bind:class="isSelected(@js($rowKey)) && 'bg-subtle!'" @endif
    {{ $attributes->class([
        'transition-colors hover:bg-subtle/60',
        '[&>td]:sticky [&>td]:top-10 [&>td]:z-[2] [&>td]:bg-card' => $sticky,
        'max-sm:relative max-sm:grid max-sm:grid-cols-2 max-sm:gap-x-2.5 max-sm:gap-y-1.5 max-sm:rounded-[14px] max-sm:border max-sm:border-line max-sm:bg-card max-sm:px-3.5 max-sm:py-3' => $stack,
    ]) }}
>
    @if ($selectable)
        <td @class(['w-11 border-b border-line-soft px-0', $cellHeight, 'max-sm:hidden' => $stack])>
            @if ($rowKey !== '')
                <span class="flex justify-center">
                    <input type="checkbox" data-table-select value="{{ $rowKey }}" x-model="selected" aria-label="Selecionar linha" class="size-4 cursor-pointer rounded-[5px] accent-ink" />
                </span>
            @endif
        </td>
    @endif
    @if ($expandable)
        <td @class(['w-11 border-b border-line-soft px-0', $cellHeight, 'max-sm:hidden' => $stack])>
            @if ($hasDetail)
                <span class="flex justify-center">
                    <button
                        type="button"
                        x-on:click="toggleRow(@js($rowKey))"
                        x-bind:aria-expanded="Boolean(expanded[@js($rowKey)])"
                        aria-label="Mostrar detalhes"
                        class="flex size-7 cursor-pointer items-center justify-center rounded-lg text-ink-3 transition hover:bg-wrap hover:text-ink"
                    >
                        <x-ui::icon name="arrow-right-01" class="size-4 transition-transform" x-bind:class="expanded[@js($rowKey)] && 'rotate-90'" />
                    </button>
                </span>
            @endif
        </td>
    @endif
    {{ $slot }}
</tr>

@if ($hasDetail)
    <tr data-table-detail x-show="expanded[@js($rowKey)]" x-cloak>
        <td colspan="100" class="border-b border-line-soft bg-subtle px-3.5 py-3.5 pl-[58px]">
            {{ $detail }}
        </td>
    </tr>
@endif
