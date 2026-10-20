<?php

use Illuminate\Support\Facades\Blade;

test('sortable list renders handles, move buttons and a live region', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::sortable-list sort="moveMilestone" label="Marcos">
            <x-ui::sortable-list.item key="m1">Cadastro de membros<x-slot:meta>entregue</x-slot:meta></x-ui::sortable-list.item>
            <x-ui::sortable-list.item key="m2" label="Dízimos">Dízimos e ofertas</x-ui::sortable-list.item>
        </x-ui::sortable-list>
        BLADE);

    expect($html)
        ->toContain('data-sortable-list')
        ->toContain("sort: 'moveMilestone'")
        ->toContain('aria-label="Marcos"')
        ->toContain('data-key="m1"')
        ->toContain('data-label="Cadastro de membros"')
        ->toContain('aria-label="Mover Dízimos"')
        ->toContain('aria-label="Mover para cima"')
        ->toContain('aria-live="polite"')
        ->toContain('entregue')
        ->and(substr_count($html, 'data-sortable-handle'))->toBe(2);

    expect(Blade::render('<x-ui::sortable-list :buttons="false"><x-ui::sortable-list.item key="a">A</x-ui::sortable-list.item></x-ui::sortable-list>'))
        ->not->toContain('Mover para cima');
});
