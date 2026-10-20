<?php

use Illuminate\Support\Facades\Blade;

test('date range renders a trigger, two months, presets and apply buttons', function () {
    $html = Blade::render('<x-ui::date-range wire:model="period" label="Período" name="period" :value="[\'start\' => \'2026-10-01\', \'end\' => \'2026-10-15\']" :presets="[[\'label\' => \'Marco M3\', \'start\' => \'2026-10-01\', \'end\' => \'2026-10-31\']]" />');

    expect($html)
        ->toContain('data-date-range')
        ->toContain('x-data="uiDateRange(')
        ->toContain('wire:model="period"')
        ->toContain('Marco M3')
        ->toContain('monthLabel(0)')
        ->toContain('monthLabel(1)')
        ->toContain('Aplicar')
        ->toContain('Cancelar')
        ->toContain('name="period[start]"')
        ->toContain('value="2026-10-15"')
        ->toContain('role="dialog"')
        ->not->toContain('role="listbox"');
});

test('date range with time shows start and end slots and duration inline', function () {
    $html = Blade::render('<x-ui::date-range inline with-time step="30" time-heading="Banco de horas" timezone="America/Bahia" />');

    expect($html)
        ->toContain('Banco de horas')
        ->toContain('role="listbox"')
        ->toContain('Duração')
        ->toContain('intervalos de 30 min')
        ->toContain('\\u0022time\\u0022:true')
        ->not->toContain('x-ref="trigger"')
        ->not->toContain('Aplicar');
});
