<?php

use Illuminate\Support\Facades\Blade;

test('number input renders a spinbutton with steppers, limits and unit', function () {
    $html = Blade::render('<x-ui::number-input label="Horas" wire:model.live="hours" value="60" min="0" max="200" step="5" unit="h" />');

    expect($html)
        ->toContain('data-number-input')
        ->toContain('x-data="uiNumber({ min: 0, max: 200, step: 5 })"')
        ->toContain('role="spinbutton"')
        ->toContain('aria-valuemin="0"')
        ->toContain('aria-valuemax="200"')
        ->toContain('inputmode="decimal"')
        ->toContain('value="60"')
        ->toContain('wire:model.live="hours"')
        ->toContain('aria-label="Diminuir Horas"')
        ->toContain('aria-label="Aumentar Horas"')
        ->toContain('x-on:keydown.arrow-up.prevent="increment()"')
        ->toContain('>h</span>')
        ->toContain('for="ui-number-hours"');
});

test('number input without limits passes null to alpine and defaults the value', function () {
    $html = Blade::render('<x-ui::number-input />');

    expect($html)->toContain('uiNumber({ min: null, max: null, step: 1 })')->toContain('value="0"');
});

test('number input sizes and error', function () {
    expect(Blade::render('<x-ui::number-input size="sm" />'))->toContain('h-9');
    expect(Blade::render('<x-ui::number-input error="Maior que zero" />'))
        ->toContain('aria-invalid="true"')
        ->toContain('Maior que zero');
});
