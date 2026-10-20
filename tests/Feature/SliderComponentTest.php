<?php

use Illuminate\Support\Facades\Blade;

test('slider renders a styled native range with label value and bubble', function () {
    $html = Blade::render('<x-ui::slider label="Cobertura" value="70" unit="%" bubble wire:model.live="coverage" />');

    expect($html)
        ->toContain('data-slider')
        ->toContain('x-data="uiSlider({ min: 0, max: 100, range: false, minDistance: 0, value: \'70\' })"')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model.live="coverage"')
        ->toContain('type="range"')
        ->toContain('class="ui-range"')
        ->toContain('x-bind:style="fillStyle()"')
        ->toContain('Cobertura')
        ->toContain('bg-ink px-[7px]');
});

test('slider with marks steps through each label', function () {
    $html = Blade::render('<x-ui::slider label="Rigor" min="0" max="3" :marks="[\'Leve\', \'Padrão\', \'Rígido\', \'Máximo\']" />');

    expect($html)
        ->toContain('step="1"')
        ->toContain('grid-template-columns: repeat(4, minmax(0, 1fr))')
        ->toContain('Leve')
        ->toContain('Máximo')
        ->toContain('marks[Math.round');
});

test('range slider renders two thumbs with minimum distance', function () {
    $html = Blade::render('<x-ui::slider label="Valor" range min="0" max="100" step="5" min-distance="10" :value="[20, 70]" />');

    expect($html)
        ->toContain("range: true, minDistance: 10, value: JSON.parse('[20,70]')")
        ->toContain('ui-range-dual')
        ->toContain('aria-label="Mínimo"')
        ->toContain('aria-label="Máximo"')
        ->toContain('setLow($el.value)')
        ->toContain('setHigh($el.value)');
    expect(substr_count($html, 'type="range"'))->toBe(2);
});
