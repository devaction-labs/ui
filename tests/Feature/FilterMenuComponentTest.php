<?php

use Illuminate\Support\Facades\Blade;

test('filter menu renders a trigger, groups with submenus and apply actions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::filter-menu wire:model.live="filters" :value="['area' => ['backend']]">
            <x-ui::filter-menu.group name="area" label="Área" icon="folder-01">
                <x-ui::filter-menu.option value="backend" color="#2563EB" count="4">Backend</x-ui::filter-menu.option>
                <x-ui::filter-menu.option>Frontend</x-ui::filter-menu.option>
            </x-ui::filter-menu.group>
            <x-ui::filter-menu.group name="prazo" label="Prazo" single>
                <x-ui::filter-menu.option value="semana">Esta semana</x-ui::filter-menu.option>
            </x-ui::filter-menu.group>
        </x-ui::filter-menu>
        BLADE);

    expect($html)
        ->toContain('data-filter-menu')
        ->toContain('uiFilterMenu({ value: JSON.parse(')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model.live="filters"')
        ->toContain('aria-haspopup="menu"')
        ->toContain('data-filter-group-trigger="area"')
        ->toContain('data-filter-group="prazo"')
        ->toContain('role="menuitemcheckbox"')
        ->toContain('role="menuitemradio"')
        ->toContain("toggleOption('area', 'backend', false)")
        ->toContain("toggleOption('area', 'Frontend', false)")
        ->toContain("toggleOption('prazo', 'semana', true)")
        ->toContain('background: #2563EB')
        ->toContain('Limpar')
        ->toContain('Aplicar')
        ->toContain('apply()');
});

test('filter menu can align to the end and use another label', function () {
    expect(Blade::render('<x-ui::filter-menu label="Filtros" align="end" />'))
        ->toContain('right-0')
        ->toContain('aria-label="Filtros"');
});
