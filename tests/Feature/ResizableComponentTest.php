<?php

use Illuminate\Support\Facades\Blade;

test('resizable renders two panels around an accessible separator', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::resizable default="58" min="30" max="75" persist="pr" class="h-72">
            <x-ui::resizable.panel>Diff</x-ui::resizable.panel>
            <x-ui::resizable.handle label="Tamanho do diff" />
            <x-ui::resizable.panel>Resumo</x-ui::resizable.panel>
        </x-ui::resizable>
        BLADE);

    expect($html)
        ->toContain('data-resizable')
        ->toContain("uiResizable({ size: 58, min: 30, max: 75, vertical: false, persist: 'pr' })")
        ->toContain('--resizable-size: 58%')
        ->toContain('role="separator"')
        ->toContain('tabindex="0"')
        ->toContain('aria-label="Tamanho do diff"')
        ->toContain('x-bind:aria-valuenow="Math.round(size)"')
        ->toContain('x-on:dblclick="reset()"')
        ->toContain('first:basis-(--resizable-size)')
        ->toContain('h-72');

    expect(Blade::render('<x-ui::resizable vertical><x-ui::resizable.panel /></x-ui::resizable>'))
        ->toContain('flex-col')
        ->toContain('vertical: true');
});
