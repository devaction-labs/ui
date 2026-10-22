<?php

use Illuminate\Support\Facades\Blade;

test('autocomplete renders a combobox input with grouped suggestions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::autocomplete wire:model.live="search" placeholder="Buscar…" label="Busca">
            <x-ui::autocomplete.group heading="Lições">
                <x-ui::autocomplete.item value="Ecto.Multi e rollback" badge="30" />
            </x-ui::autocomplete.group>
            <x-ui::autocomplete.item value="Dízimos" prefix="#142" />
        </x-ui::autocomplete>
        BLADE);

    expect($html)
        ->toContain('data-autocomplete')
        ->toContain('x-data="uiAutocomplete({ minChars: 1, filter: true })"')
        ->toContain('role="combobox"')
        ->toContain('aria-autocomplete="list"')
        ->toContain('wire:model.live="search"')
        ->toContain('placeholder="Buscar…"')
        ->toContain('id="ui-autocomplete-search"')
        ->toContain('role="listbox"')
        ->toContain('data-autocomplete-group')
        ->toContain('aria-label="Lições"')
        ->toContain('data-value="Ecto.Multi e rollback"')
        ->toContain('>30</span>')
        ->toContain('data-search="Dízimos #142"')
        ->toContain('x-html="highlight(');
    expect(substr_count($html, 'role="option"'))->toBe(2);
});

test('autocomplete items can always show and keep the text', function () {
    $html = Blade::render('<x-ui::autocomplete><x-ui::autocomplete.item always keep variant="ai" label="Perguntar à IA" kbd="⌘↵" /></x-ui::autocomplete>');

    expect($html)
        ->toContain('data-autocomplete-always')
        ->toContain('data-autocomplete-keep')
        ->toContain('bg-ai')
        ->toContain('⌘↵');
});

test('autocomplete shows errors and empty text', function () {
    expect(Blade::render('<x-ui::autocomplete error="Obrigatório" empty="Nada" min-chars="0" />'))
        ->toContain('aria-invalid="true"')
        ->toContain('Obrigatório')
        ->toContain('Nada')
        ->toContain('minChars: 0');
});

test('autocomplete listbox has no x-transition, which throws cancelled-transition errors in the Alpine console', function () {
    $html = Blade::render('<x-ui::autocomplete label="Busca"><x-ui::autocomplete.item value="A" /></x-ui::autocomplete>');

    expect($html)
        ->toContain('x-show="open && items().length"')
        ->not->toContain('x-transition');
});
