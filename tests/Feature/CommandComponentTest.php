<?php

use Illuminate\Support\Facades\Blade;

test('command palette renders a combobox with grouped options', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::command>
            <x-ui::command.input placeholder="Buscar" />
            <x-ui::command.items>
                <x-ui::command.group heading="Ações">
                    <x-ui::command.item icon="git-pull-request" description="sge-api #214" kbd="↵" keywords="review">Abrir PR</x-ui::command.item>
                </x-ui::command.group>
                <x-ui::command.empty>Nada encontrado.</x-ui::command.empty>
            </x-ui::command.items>
        </x-ui::command>
        BLADE);

    expect($html)
        ->toContain('data-command')
        ->toContain('x-data="uiCommand"')
        ->toContain('role="combobox"')
        ->toContain('x-model="query"')
        ->toContain('x-on:keydown.arrow-down.prevent="move(1)"')
        ->toContain('x-on:keydown.enter.prevent="choose()"')
        ->toContain('x-bind:aria-activedescendant="activeId"')
        ->toContain('placeholder="Buscar"')
        ->toContain('>Esc</kbd>')
        ->toContain('role="listbox"')
        ->toContain('x-show="groupHasResults($el)"')
        ->toContain('role="option"')
        ->toContain('data-search="Abrir PR sge-api #214 review"')
        ->toContain('x-show="isVisible($el)"')
        ->toContain("x-on:click=\"\$dispatch('ui-modal-close-self')\"")
        ->toContain('data-icon="git-pull-request"')
        ->toContain('x-show="! hasResults()"')
        ->toContain('Nada encontrado.');
});

test('command item can stay open, be a link and hide the input kbd', function () {
    expect(Blade::render('<x-ui::command.item :close="false">x</x-ui::command.item>'))->not->toContain('ui-modal-close-self');
    expect(Blade::render('<x-ui::command.item href="/projetos/sge">SGE</x-ui::command.item>'))->toContain('<a')->toContain('href="/projetos/sge"');
    expect(Blade::render('<x-ui::command.input :kbd="false" />'))->not->toContain('<kbd');
});
