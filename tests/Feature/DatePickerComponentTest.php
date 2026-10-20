<?php

use Illuminate\Support\Facades\Blade;

test('date picker renders a trigger and a calendar dialog', function () {
    $html = Blade::render('<x-ui::date-picker label="Vencimento" wire:model.live="dueDate" value="2026-10-23" min="2026-10-19" />');

    expect($html)
        ->toContain('data-date-picker')
        ->toContain('uiDatePicker(')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model.live="dueDate"')
        ->toContain('id="ui-date-duedate"')
        ->toContain('aria-haspopup="dialog"')
        ->toContain('role="dialog"')
        ->toContain('role="grid"')
        ->toContain('x-on:keydown.arrow-left.prevent="moveFocus(-1)"')
        ->toContain('preset(nextWeekday(5))')
        ->toContain('x-text="relative(start())"')
        ->toContain('data-icon="calendar-03"');
});

test('range date picker uses range presets and hidden inputs', function () {
    $html = Blade::render('<x-ui::date-picker range name="period" :value="[\'start\' => \'2026-10-13\', \'end\' => \'2026-10-24\']" />');

    expect($html)
        ->toContain('Últimos 7 dias')
        ->toContain('name="period[start]" value="2026-10-13"')
        ->toContain('name="period[end]" value="2026-10-24"')
        ->not->toContain('relative(start())');
});

test('inline date picker shows only the calendar', function () {
    $html = Blade::render('<x-ui::date-picker inline value="2026-10-16" :presets="false" />');

    expect($html)
        ->toContain('data-date-calendar')
        ->not->toContain('aria-haspopup')
        ->not->toContain('Amanhã');
});

test('date picker shows errors', function () {
    expect(Blade::render('<x-ui::date-picker error="Escolha o vencimento" />'))
        ->toContain('aria-invalid="true"')
        ->toContain('Escolha o vencimento');
});
