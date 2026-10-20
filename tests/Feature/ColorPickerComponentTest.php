<?php

use Illuminate\Support\Facades\Blade;

test('color picker renders a hex field with a swatch and a panel', function () {
    $html = Blade::render('<x-ui::color-picker label="Cor do projeto" wire:model.live="color" value="#2563EB" />');

    expect($html)
        ->toContain('data-color-picker')
        ->toContain("uiColorPicker({ value: '#2563EB', alpha: false })")
        ->toContain('x-modelable="value"')
        ->toContain('wire:model.live="color"')
        ->toContain('id="ui-color-color"')
        ->toContain('aria-label="Abrir seletor de cor"')
        ->toContain('role="dialog"')
        ->toContain('aria-label="Saturação e brilho"')
        ->toContain('class="ui-color-range"')
        ->toContain('role="radiogroup"')
        ->toContain('aria-label="#C2850F"')
        ->not->toContain('Opacidade');
});

test('inline color picker with alpha and custom presets', function () {
    $html = Blade::render('<x-ui::color-picker inline alpha name="brand" :presets="[\'#000000\']" />');

    expect($html)
        ->toContain('Opacidade')
        ->toContain('name="brand"')
        ->toContain('aria-label="#000000"')
        ->not->toContain('aria-label="#C2850F"')
        ->not->toContain('Abrir seletor de cor');
});

test('color picker without presets hides the grid', function () {
    expect(Blade::render('<x-ui::color-picker :presets="[]" />'))->not->toContain('Cores prontas');
});
