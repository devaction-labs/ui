<?php

use Illuminate\Support\Facades\Blade;

test('description list renders label and value pairs', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::description-list label-width="140px">
            <x-ui::description-list.item label="Cliente">Igreja Viva</x-ui::description-list.item>
            <x-ui::description-list.item label="Repositório" mono copyable>devaction-labs/sge-api</x-ui::description-list.item>
        </x-ui::description-list>
        BLADE);

    expect($html)
        ->toContain('<dl')
        ->toContain('--description-label: 140px')
        ->toContain('<dt class="text-muted">Cliente</dt>')
        ->toContain('Igreja Viva')
        ->toContain('font-mono text-[13px]')
        ->toContain('aria-label="Copiar Repositório"')
        ->toContain('sm:grid-cols-[var(--description-label)_minmax(0,1fr)]');
});

test('description list in two columns stacks the label', function () {
    $html = Blade::render('<x-ui::description-list columns="2"><x-ui::description-list.item label="E-mail">a@b.c</x-ui::description-list.item></x-ui::description-list>');

    expect($html)
        ->toContain('sm:grid-cols-2')
        ->not->toContain('var(--description-label)_minmax');
});
