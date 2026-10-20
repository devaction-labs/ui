<?php

use Illuminate\Support\Facades\Blade;

test('combobox renders a trigger, search and listbox with options', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::combobox label="Projeto" wire:model.live="project" search-placeholder="Filtrar projetos…" empty="Nenhum projeto.">
            <x-ui::combobox.option value="igreja" color="#2563EB" meta="12 PRs">Igreja Viva</x-ui::combobox.option>
            <x-ui::combobox.option value="loja" icon="folder-01" search="ecommerce">Loja Ponto</x-ui::combobox.option>
        </x-ui::combobox>
        BLADE);

    expect($html)
        ->toContain('data-combobox')
        ->toContain('x-data="uiCombobox({ value: null, multiple: false, filter: true })"')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model.live="project"')
        ->toContain('aria-haspopup="listbox"')
        ->toContain('id="ui-combobox-project"')
        ->toContain('role="combobox"')
        ->toContain('placeholder="Filtrar projetos…"')
        ->toContain('role="listbox"')
        ->toContain('Nenhum projeto.')
        ->toContain('data-value="igreja"')
        ->toContain('data-search="Igreja Viva 12 PRs"')
        ->toContain('data-search="Loja Ponto  ecommerce"')
        ->toContain('background: #2563EB')
        ->toContain('data-icon="folder-01"');
    expect(substr_count($html, 'role="option"'))->toBe(2);
});

test('combobox with name keeps a hidden input for plain forms', function () {
    expect(Blade::render('<x-ui::combobox name="owner" value="ana"><x-ui::combobox.option value="ana">Ana</x-ui::combobox.option></x-ui::combobox>'))
        ->toMatch('/type="hidden"[^>]*name="owner"[^>]*value="ana"/s')
        ->toContain("uiCombobox({ value: 'ana', multiple: false, filter: true })");
});

test('combobox shows errors and disabled options', function () {
    $html = Blade::render('<x-ui::combobox error="Escolha alguém"><x-ui::combobox.option value="x" disabled>X</x-ui::combobox.option></x-ui::combobox>');

    expect($html)
        ->toContain('aria-invalid="true"')
        ->toContain('Escolha alguém')
        ->toMatch('/role="option"[^>]*\sdisabled[\s>]/s');
});
