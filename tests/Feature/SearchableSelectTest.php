<?php

use Illuminate\Support\Facades\Blade;

test('select listbox variant renders a searchable multiselect list', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::select variant="listbox" searchable multiple placeholder="Setores" name="setores" :value="['saude']">
            <x-ui::select.option value="saude">Saúde</x-ui::select.option>
            <x-ui::select.option value="varejo" icon="tag-01">Varejo</x-ui::select.option>
        </x-ui::select>
        BLADE);

    expect($html)
        ->toContain('data-combobox')
        ->toContain('data-multiple')
        ->toContain('multiple: true')
        ->toContain('filter: true')
        ->toContain('data-combobox-search')
        ->toContain('aria-multiselectable="true"')
        ->toContain('data-value="varejo"')
        ->toContain('name="setores[]"')
        ->not->toContain('<option');
});

test('select listbox without searchable has no search box and native select stays native', function () {
    expect(Blade::render('<x-ui::select variant="listbox"><x-ui::select.option value="a">A</x-ui::select.option></x-ui::select>'))
        ->toContain('data-combobox-option')
        ->not->toContain('data-combobox-search');

    expect(Blade::render('<x-ui::select><x-ui::select.option value="a">A</x-ui::select.option></x-ui::select>'))
        ->toContain('<option value="a">A</option>')
        ->not->toContain('data-combobox');
});

test('combobox server search disables client filtering and uses the input slot', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::combobox wire:model="companyId" :filter="false">
            <x-slot:input><x-ui::combobox.input wire:model.live.debounce.250ms="companySearch" placeholder="Buscar empresa…" /></x-slot:input>
            <x-ui::combobox.option value="1">Acme</x-ui::combobox.option>
            <x-slot:empty><x-ui::combobox.option.empty when-loading="Buscando…">Nenhuma empresa.</x-ui::combobox.option.empty></x-slot:empty>
        </x-ui::combobox>
        BLADE);

    expect($html)
        ->toContain('filter: false')
        ->toContain('wire:model.live.debounce.250ms="companySearch"')
        ->toContain('placeholder="Buscar empresa…"')
        ->toContain('Buscando…')
        ->toContain('Nenhuma empresa.')
        ->and(substr_count($html, 'data-combobox-search'))->toBe(1)
        ->and(substr_count($html, 'data-combobox-empty'))->toBe(1);
});

test('pillbox with options works like a multiselect with create option', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::pillbox wire:model.live="selectedTags" variant="combobox" :filter="false">
            <x-slot:input><x-ui::pillbox.input wire:model.live="search" placeholder="Escolha as tags…" /></x-slot:input>
            <x-ui::pillbox.option value="1" selected-label="VIP">VIP · cliente</x-ui::pillbox.option>
            <x-ui::pillbox.option.create wire:click="createTag" min-length="2">Criar "<span wire:text="search"></span>"</x-ui::pillbox.option.create>
        </x-ui::pillbox>
        BLADE);

    expect($html)
        ->toContain('data-pillbox-options')
        ->toContain('clearQuery: true')
        ->toContain('filter: false')
        ->toContain('wire:model.live="search"')
        ->toContain('data-label="VIP"')
        ->toContain('data-combobox-create')
        ->toContain('wire:click="createTag"')
        ->toContain('query.trim().length >= 2')
        ->toContain('<span wire:text="search"></span>');
});

test('pillbox searchable shows a search box inside the list', function () {
    $html = Blade::render('<x-ui::pillbox searchable search-placeholder="Buscar tags…"><x-ui::pillbox.option value="a">A</x-ui::pillbox.option></x-ui::pillbox>');

    expect($html)
        ->toContain('data-pillbox-options')
        ->toContain('clearQuery: false')
        ->toContain('placeholder="Buscar tags…"');
});

test('pillbox without options keeps free text mode', function () {
    expect(Blade::render('<x-ui::pillbox :suggestions="[\'VIP\']" />'))
        ->toContain('uiPillbox(')
        ->not->toContain('data-pillbox-options');
});

test('autocomplete can leave filtering to the server', function () {
    expect(Blade::render('<x-ui::autocomplete wire:model.live="contactSearch" :filter="false"><x-ui::autocomplete.item value="Ana" /></x-ui::autocomplete>'))
        ->toContain('filter: false')
        ->toContain('wire:model.live="contactSearch"');

    expect(Blade::render('<x-ui::autocomplete><x-ui::autocomplete.item value="Ana" /></x-ui::autocomplete>'))->toContain('filter: true');
});
