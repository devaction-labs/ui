{{--
    Linhas de carregamento da tabela.

    <x-ui::table.rows wire:loading>
        <x-ui::table.skeleton rows="4" columns="3" />
    </x-ui::table.rows>
--}}
@aware([
    'density' => 'default',
    'selectable' => false,
    'expandable' => false,
])

@props([
    'rows' => 4,
    'columns' => 3,
])

@php
    $widths = [72, 48, 60, 38, 66, 54];
@endphp

@for ($row = 0; $row < (int) $rows; $row++)
    <tr data-table-skeleton aria-hidden="true">
        @if ($selectable)
            <td class="w-11 border-b border-line-soft"></td>
        @endif
        @if ($expandable)
            <td class="w-11 border-b border-line-soft"></td>
        @endif
        @for ($column = 0; $column < (int) $columns; $column++)
            <td @class(['border-b border-line-soft px-3.5', 'h-12' => $density !== 'compact', 'h-9' => $density === 'compact'])>
                <x-ui::skeleton.line style="width: {{ $widths[($row + $column) % count($widths)] }}%" />
            </td>
        @endfor
    </tr>
@endfor
