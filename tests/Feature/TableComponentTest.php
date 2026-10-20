<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

test('table renders native table markup with sortable columns', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::table>
            <x-ui::table.columns>
                <x-ui::table.column sortable sorted direction="desc" wire:click="sort('name')">Projeto</x-ui::table.column>
                <x-ui::table.column sortable>Cliente</x-ui::table.column>
                <x-ui::table.column align="end">Horas</x-ui::table.column>
            </x-ui::table.columns>
            <x-ui::table.rows>
                <x-ui::table.row key="1">
                    <x-ui::table.cell primary>Igreja Viva</x-ui::table.cell>
                    <x-ui::table.cell>Pr. Marcos</x-ui::table.cell>
                    <x-ui::table.cell align="end">132 h</x-ui::table.cell>
                </x-ui::table.row>
            </x-ui::table.rows>
        </x-ui::table>
        BLADE);

    expect($html)
        ->toContain('data-table')
        ->toContain('x-data="uiTable"')
        ->toContain('<table')
        ->toContain('<thead')
        ->toContain('scope="col"')
        ->toMatch('/aria-sort="descending"[^>]*wire:click="sort\(\'name\'\)"/s')
        ->toContain('aria-sort="none"')
        ->toContain('↓')
        ->toContain('<tbody')
        ->toContain('wire:key="table-row-1"')
        ->toContain('font-medium text-ink')
        ->toContain('text-right tabular-nums')
        ->toContain('rounded-xl border border-line');
});

test('selectable table adds checkboxes and a bulk bar', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::table selectable wire:model.live="selected">
            <x-slot:bulk><button>Arquivar</button></x-slot:bulk>
            <x-ui::table.columns><x-ui::table.column>Tarefa</x-ui::table.column></x-ui::table.columns>
            <x-ui::table.rows>
                <x-ui::table.row key="7"><x-ui::table.cell>Corrigir</x-ui::table.cell></x-ui::table.row>
            </x-ui::table.rows>
        </x-ui::table>
        BLADE);

    expect($html)
        ->toContain('wire:model.live="selected"')
        ->toContain('aria-label="Selecionar todas"')
        ->toContain('$el.indeterminate')
        ->toMatch('/data-table-select value="7" x-model="selected"/')
        ->toContain('<button>Arquivar</button>')
        ->toContain('clearSelection()');
});

test('expandable rows render a toggle and a detail row', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::table expandable variant="minimal">
            <x-ui::table.columns><x-ui::table.column>PR</x-ui::table.column></x-ui::table.columns>
            <x-ui::table.rows>
                <x-ui::table.row key="142">
                    <x-ui::table.cell>#142</x-ui::table.cell>
                    <x-slot:detail>Achados</x-slot:detail>
                </x-ui::table.row>
            </x-ui::table.rows>
        </x-ui::table>
        BLADE);

    expect($html)
        ->toContain("toggleRow('142')")
        ->toContain('aria-label="Mostrar detalhes"')
        ->toContain('data-table-detail')
        ->toContain('colspan="100"')
        ->toContain('Achados')
        ->not->toContain('rounded-xl border border-line');
});

test('table groups, empty, skeleton and pagination footer', function () {
    $paginator = new LengthAwarePaginator(range(1, 4), 23, 4, 2, ['path' => '/reviews']);

    $html = Blade::render(<<<'BLADE'
        <x-ui::table :paginator="$paginator" density="compact" striped>
            <x-ui::table.columns><x-ui::table.column>Review</x-ui::table.column></x-ui::table.columns>
            <x-ui::table.group heading="SGE" count="2" color="#2563EB">
                <x-ui::table.row key="a"><x-ui::table.cell>A</x-ui::table.cell></x-ui::table.row>
            </x-ui::table.group>
            <x-ui::table.rows>
                <x-ui::table.skeleton rows="2" columns="1" />
                <x-ui::table.empty heading="Nada aqui" text="Tente outro filtro." />
            </x-ui::table.rows>
        </x-ui::table>
        BLADE, ['paginator' => $paginator]);

    expect($html)
        ->toContain('data-table-group')
        ->toContain('scope="rowgroup"')
        ->toContain('x-bind:aria-expanded="groupOpen"')
        ->toContain('h-9')
        ->toContain('[&amp;&gt;tr[data-table-row]:nth-of-type(even)]:bg-subtle')
        ->toContain('Nada aqui')
        ->toContain('aria-label="Paginação"')
        ->toContain('href="/reviews?page=3"');
    expect(substr_count($html, 'data-table-skeleton'))->toBe(2);
});

test('stack and sticky cells', function () {
    $html = Blade::render('<x-ui::table stack><x-ui::table.rows><x-ui::table.row><x-ui::table.cell primary sticky>A</x-ui::table.cell><x-ui::table.cell label="Cliente">B</x-ui::table.cell></x-ui::table.row></x-ui::table.rows></x-ui::table>');

    expect($html)
        ->toContain('sticky left-0')
        ->toContain('data-label="Cliente"')
        ->toContain('max-sm:before:content-[attr(data-label)]')
        ->toContain('max-sm:grid');
});

test('table follows the flux api: paginate, scroll-to, container class, bleed, sticky header and strong cells', function () {
    $paginator = new LengthAwarePaginator([['id' => 1]], 12, 5, 1);

    $html = Blade::render(<<<'BLADE'
        <x-ui::table :paginate="$paginator" :livewire="true" pagination:scroll-to="#pedidos" container:class="max-h-80" bleed>
            <x-ui::table.columns sticky>
                <x-ui::table.column>Cliente</x-ui::table.column>
            </x-ui::table.columns>
            <x-ui::table.rows>
                <x-ui::table.row key="1" sticky>
                    <x-ui::table.cell variant="strong">R$ 1.290</x-ui::table.cell>
                </x-ui::table.row>
            </x-ui::table.rows>
        </x-ui::table>
        BLADE, ['paginator' => $paginator]);

    expect($html)
        ->toContain('overflow-auto max-h-80')
        ->toContain('--ui-table-bleed: var(--ui-bleed, 1.5rem)')
        ->toContain(':pl-(--ui-table-bleed)')
        ->toContain('th]:sticky')
        ->toContain('td]:top-10')
        ->toContain('font-medium text-ink')
        ->toContain('wire:click="setPage(2')
        ->toContain('scrollIntoView')
        ->toContain('#pedidos')
        ->not->toContain('pagination:scroll-to=')
        ->not->toContain('container:class=');
});

test('table pagination uses links outside livewire', function () {
    $paginator = new LengthAwarePaginator([['id' => 1]], 12, 5, 1, ['path' => '/pedidos']);

    expect(Blade::render('<x-ui::table :paginate="$paginator" />', ['paginator' => $paginator]))
        ->toContain('href="/pedidos?page=2"')
        ->not->toContain('setPage(');
});
