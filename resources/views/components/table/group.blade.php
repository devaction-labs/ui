{{--
    Grupo de linhas com título que abre e fecha. Use no lugar de table.rows (um por grupo).

    <x-ui::table.group heading="SGE · Campo Limpo" count="4 tarefas" color="#2563EB">
        …linhas…
    </x-ui::table.group>
--}}
@aware([
    'striped' => false,
])

@props([
    'heading',
    'count' => null,
    'color' => null,
    'expanded' => true,
])

<tbody
    data-table-group
    x-data="{ groupOpen: @js((bool) $expanded) }"
    x-bind:data-collapsed="groupOpen ? null : ''"
    {{ $attributes->class([
        'data-collapsed:[&>tr:not(:first-child)]:hidden',
        '[&>tr[data-table-row]:nth-of-type(odd)]:bg-subtle' => $striped,
    ]) }}
>
    <tr>
        <th colspan="100" scope="rowgroup" class="h-10 border-b border-line-soft bg-subtle px-2 text-left">
            <button
                type="button"
                x-on:click="groupOpen = ! groupOpen"
                x-bind:aria-expanded="groupOpen"
                class="inline-flex cursor-pointer items-center gap-2 rounded-lg px-1.5 py-1 text-[13px] font-semibold text-ink transition hover:bg-wrap"
            >
                <x-ui::icon name="arrow-down-01" class="size-3.5 text-muted transition-transform" x-bind:class="! groupOpen && '-rotate-90'" />
                @if ($color)
                    <span class="size-2 rounded-[2px]" style="background: {{ $color }}" aria-hidden="true"></span>
                @endif
                {{ $heading }}
                @if ($count !== null)
                    <span class="font-mono text-[11px] font-normal text-muted">{{ $count }}</span>
                @endif
            </button>
        </th>
    </tr>
    {{ $slot }}
</tbody>
