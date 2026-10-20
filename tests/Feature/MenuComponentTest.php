<?php

use Illuminate\Support\Facades\Blade;

test('menu renders an accessible panel inside the dropdown', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::dropdown align="end">
            <x-ui::button>Ações</x-ui::button>
            <x-ui::menu>
                <x-ui::menu.item icon="download-04" kbd="⌘E" suffix="3">Exportar CSV</x-ui::menu.item>
            </x-ui::menu>
        </x-ui::dropdown>
        BLADE);

    expect($html)
        ->toContain('data-menu')
        ->toContain('role="menu"')
        ->toContain('x-data="uiMenu"')
        ->toContain('x-on:keydown="onKeydown($event)"')
        ->toContain('w-60')
        ->toContain('right-(--dropdown-offset)')
        ->toContain('role="menuitem"')
        ->toContain('tabindex="-1"')
        ->toContain('data-icon="download-04"')
        ->toContain('>⌘E</kbd>')
        ->toContain('>3</span>')
        ->toContain('close()');
});

test('menu item supports danger, links and disabled', function () {
    expect(Blade::render('<x-ui::menu.item variant="danger">Excluir</x-ui::menu.item>'))->toContain('text-risk-fg');
    expect(Blade::render('<x-ui::menu.item href="/pr/142">Abrir</x-ui::menu.item>'))->toContain('<a')->toContain('href="/pr/142"');
    expect(Blade::render('<x-ui::menu.item disabled>Bloqueado</x-ui::menu.item>'))->toMatch('/data-menu-item[^>]*\sdisabled[\s>]/');
});

test('keep open prevents closing at menu or item level', function () {
    expect(Blade::render('<x-ui::menu keep-open><x-ui::menu.item>x</x-ui::menu.item></x-ui::menu>'))->toContain('data-keep-open');
    expect(Blade::render('<x-ui::menu.item keep-open>x</x-ui::menu.item>'))->toContain('if (! true');
});

test('separator and group render their roles', function () {
    expect(Blade::render('<x-ui::menu.separator />'))->toContain('role="separator"');
    expect(Blade::render('<x-ui::menu.group heading="Projeto"><x-ui::menu.item>x</x-ui::menu.item></x-ui::menu.group>'))
        ->toMatch('/role="group"\s+aria-label="Projeto"/')
        ->toContain('uppercase">Projeto</div>');
});

test('submenu opens beside with keyboard support', function () {
    $html = Blade::render('<x-ui::menu.submenu heading="Mover para" icon="folder-01"><x-ui::menu.item>SGE</x-ui::menu.item></x-ui::menu.submenu>');

    expect($html)
        ->toContain('x-data="{ subOpen: false }"')
        ->toContain('data-submenu-trigger')
        ->toContain('aria-haspopup="menu"')
        ->toContain('x-data="uiMenu({ submenu: true })"')
        ->toContain('x-show="subOpen"')
        ->toContain('left-full')
        ->toContain('data-icon="arrow-right-01"');
});

test('menu checkbox toggles and binds with wire:model', function () {
    $html = Blade::render('<x-ui::menu.checkbox checked wire:model.live="drafts">Rascunhos</x-ui::menu.checkbox>');

    expect($html)
        ->toContain('role="menuitemcheckbox"')
        ->toContain('x-data="{ checked: true }"')
        ->toContain('x-modelable="checked"')
        ->toContain('aria-checked="true"')
        ->toContain('wire:model.live="drafts"');
});

test('menu radio group picks a single value', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::menu.radio.group wire:model.live="sort">
            <x-ui::menu.radio value="prazo" checked>Por prazo</x-ui::menu.radio>
            <x-ui::menu.radio value="valor">Por valor</x-ui::menu.radio>
        </x-ui::menu.radio.group>
        BLADE);

    expect($html)
        ->toContain('x-data="{ value: null }"')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model.live="sort"')
        ->toContain('role="menuitemradio"')
        ->toContain("x-init=\"value = 'prazo'\"")
        ->toContain("x-on:click=\"value = 'valor'");
});
