<?php

use Illuminate\Support\Facades\Blade;

test('context menu opens the menu on right click and from the keyboard', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::context-menu class="rounded-xl">
            <div tabindex="0">Linha</div>
            <x-ui::menu>
                <x-ui::menu.item icon="view">Abrir</x-ui::menu.item>
            </x-ui::menu>
        </x-ui::context-menu>
        BLADE);

    expect($html)
        ->toContain('data-context-menu')
        ->toContain('x-data="uiContextMenu"')
        ->toContain('x-on:contextmenu="openAt($event)"')
        ->toContain('x-on:keydown.shift.f10.prevent="openFromKeyboard()"')
        ->toContain('x-on:keydown.context-menu.prevent="openFromKeyboard()"')
        ->toContain('role="menu"')
        ->toContain('rounded-xl');
});

test('submenus anchor to their parent item, not to the context menu point', function () {
    expect(file_get_contents(dirname(__DIR__, 2).'/resources/views/components/menu/submenu.blade.php'))
        ->toContain('reference: $el.parentElement');

    expect(file_get_contents(dirname(__DIR__, 2).'/resources/js/devaction-ui.js'))
        ->toContain('Alpine.$data(el).floatReference');
});
