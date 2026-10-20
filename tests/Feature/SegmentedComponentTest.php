<?php

use Illuminate\Support\Facades\Blade;

test('segmented renders a radiogroup with hidden native radios', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::segmented label="Visualização" name="view" wire:model.live="view">
            <x-ui::segmented.option value="lista" icon="menu-01" checked>Lista</x-ui::segmented.option>
            <x-ui::segmented.option value="quadro" count="4">Quadro</x-ui::segmented.option>
        </x-ui::segmented>
        BLADE);

    expect($html)
        ->toContain('data-segmented')
        ->toContain('role="radiogroup"')
        ->toContain('aria-label="Visualização"')
        ->toContain('x-data="uiChoiceGroup({ multiple: false })"')
        ->toContain('wire:model.live="view"')
        ->toContain('inline-flex')
        ->toContain('type="radio"')
        ->toContain('name="view"')
        ->toContain('value="lista"')
        ->toContain('sr-only')
        ->toContain('has-checked:bg-card')
        ->toContain('data-icon="menu-01"')
        ->toContain('>4</span>');
    expect(substr_count($html, 'name="view"'))->toBe(2);
});

test('segmented without name leaves the shared name to the choice group script', function () {
    $html = Blade::render('<x-ui::segmented label="Tema"><x-ui::segmented.option>Claro</x-ui::segmented.option><x-ui::segmented.option>Escuro</x-ui::segmented.option></x-ui::segmented>');

    expect($html)
        ->toContain('value="Claro"')
        ->not->toContain('name=')
        ->toContain('uiChoiceGroup');
});

test('segmented sizes and full width', function () {
    expect(Blade::render('<x-ui::segmented size="sm"><x-ui::segmented.option>A</x-ui::segmented.option></x-ui::segmented>'))->toContain('h-7 rounded-md');
    expect(Blade::render('<x-ui::segmented full><x-ui::segmented.option>A</x-ui::segmented.option></x-ui::segmented>'))
        ->toContain('flex w-full')
        ->toContain('flex-1');
});

test('icon only option puts the aria label on the radio', function () {
    expect(Blade::render('<x-ui::segmented><x-ui::segmented.option value="lista" icon="menu-01" aria-label="Lista" /></x-ui::segmented>'))
        ->toMatch('/type="radio"[^>]*aria-label="Lista"/s');
});
