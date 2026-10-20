<?php

use Illuminate\Support\Facades\Blade;

test('pillbox renders pills from the alpine array with remove buttons', function () {
    $html = Blade::render('<x-ui::pillbox label="Stack" wire:model="stack" :value="[\'Elixir\', \'Phoenix\']" />');

    expect($html)
        ->toContain('data-pillbox')
        ->toContain('uiPillbox({ value: JSON.parse(')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model="stack"')
        ->toContain('x-for="(pill, index) in value"')
        ->toContain("x-bind:aria-label=\"'Remover ' + pill\"")
        ->toContain('x-on:keydown.enter.prevent="add()"')
        ->toContain('x-on:keydown.backspace="backspace()"')
        ->toContain('id="ui-pillbox-stack"')
        ->not->toContain('role="listbox"')
        ->not->toContain('type="hidden"');
});

test('pillbox with suggestions, max and name', function () {
    $html = Blade::render('<x-ui::pillbox name="tags" max="3" :suggestions="[\'VIP\', \'Lead\']" />');

    expect($html)
        ->toContain('max: 3')
        ->toContain('role="combobox"')
        ->toContain('role="listbox"')
        ->toContain('x-for="(item, index) in filtered()"')
        ->toContain('name="tags[]"');
});

test('pillbox shows errors', function () {
    expect(Blade::render('<x-ui::pillbox error="Adicione uma etiqueta" />'))
        ->toContain('aria-invalid="true"')
        ->toContain('Adicione uma etiqueta');
});
