{{--
    Menu de filtros com submenus: marque à vontade e aplique de uma vez.

    <x-ui::filter-menu wire:model.live="filters">
        <x-ui::filter-menu.group name="status" label="Status" icon="checkmark-circle-02">
            <x-ui::filter-menu.option value="aberta">Aberta</x-ui::filter-menu.option>
            <x-ui::filter-menu.option value="feita">Feita</x-ui::filter-menu.option>
        </x-ui::filter-menu.group>
        <x-ui::filter-menu.group name="prazo" label="Prazo" icon="calendar-03" single>…</x-ui::filter-menu.group>
    </x-ui::filter-menu>

    value / wire:model: ['status' => ['aberta'], 'prazo' => ['semana']]
    O evento filters-applied é disparado ao aplicar, com os filtros no detail.
    align: start · end (lado em que o painel abre)
--}}
@props([
    'label' => 'Filtrar',
    'value' => [],
    'align' => 'start',
])

<div
    data-filter-menu
    x-data="uiFilterMenu({ value: @js((object) $value) })"
    x-modelable="value"
    x-on:click.outside="close()"
    x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); escape() }"
    {{ $attributes->class(['relative inline-flex']) }}
>
    <button
        type="button"
        x-ref="trigger"
        x-on:click="toggle()"
        aria-haspopup="menu"
        x-bind:aria-expanded="open"
        class="inline-flex h-[38px] cursor-pointer items-center gap-2 rounded-[10px] border bg-card px-3 text-sm text-ink transition hover:bg-subtle"
        x-bind:class="appliedCount() || open ? 'border-ink' : 'border-line-strong'"
    >
        <x-ui::icon name="filter" class="size-4" />
        {{ $label }}
        <span
            x-show="appliedCount()"
            x-cloak
            x-text="appliedCount()"
            class="flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-ink px-[5px] text-[11px] font-semibold text-on-ink"
        ></span>
    </button>

    <div
        role="menu"
        aria-label="{{ $label }}"
        x-show="open"
        x-ui-float="{ align: @js($align === 'end' ? 'end' : 'start') }"
        x-cloak
        x-transition.opacity.duration.100ms
        @class([
            'absolute top-full z-50 mt-1.5 flex w-[260px] flex-col rounded-[14px] border border-line bg-card shadow-[0_16px_40px_rgb(24_24_27/0.14)]',
            'left-0' => $align !== 'end',
            'right-0' => $align === 'end',
        ])
    >
        <div class="flex items-center justify-between pt-2.5 pr-2.5 pb-1.5 pl-3.5">
            <span class="text-sm font-semibold text-ink">{{ $label }}</span>
            <button type="button" x-on:click="close(true)" aria-label="Fechar" class="flex size-[30px] cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-wrap hover:text-ink">
                <x-ui::icon name="cancel-01" class="size-3.5" />
            </button>
        </div>

        <div class="flex flex-col px-1.5 pb-1.5">
            {{ $slot }}
        </div>

        <div class="grid grid-cols-2 gap-2 border-t border-line-soft p-2.5">
            <x-ui::button size="sm" x-on:click="clear()">Limpar</x-ui::button>
            <x-ui::button size="sm" variant="primary" x-on:click="apply()">Aplicar</x-ui::button>
        </div>
    </div>
</div>
