<?php

use Illuminate\Support\Facades\Blade;

test('classic layout has the sidebar, a header with the toggle and the content panel', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::layout.classic collapsed>
            <x-slot:sidebar><aside>menu</aside></x-slot:sidebar>
            <x-slot:header>Trabalho / Projetos</x-slot:header>
            <x-slot:actions><button>ação</button></x-slot:actions>
            Conteúdo
        </x-ui::layout.classic>
        BLADE);

    expect($html)
        ->toContain('data-layout="classic"')
        ->toContain('$store.uiSidebar.collapsed = true')
        ->toContain('x-on:keydown.cmd.b.window.prevent')
        ->toContain('data-sidebar-toggle')
        ->toContain('Trabalho / Projetos')
        ->toContain('<aside>menu</aside>')
        ->toContain('Conteúdo');
});

test('topbar, split, centered and docs layouts render their regions', function () {
    expect(Blade::render("<x-ui::layout.topbar>\n<x-slot:nav><x-ui::layout.topbar.item href=\"/p\" current>Projetos</x-ui::layout.topbar.item></x-slot:nav>\nx\n</x-ui::layout.topbar>"))
        ->toContain('data-layout="topbar"')
        ->toContain('aria-current="page"')
        ->toContain('aria-label="Principal"');

    expect(Blade::render("<x-ui::layout.split list-width=\"300px\">\n<x-slot:list>itens</x-slot:list>\ndetalhe\n</x-ui::layout.split>"))
        ->toContain('data-layout="split"')
        ->toContain('min(100%, 300px)')
        ->toContain('itens')
        ->toContain('detalhe');

    expect(Blade::render('<x-ui::layout.centered width="640px">form</x-ui::layout.centered>'))
        ->toContain('data-layout="centered"')
        ->toContain('max-width: 640px');

    expect(Blade::render("<x-ui::layout.docs>\n<x-slot:toc><a href=\"#a\">A</a></x-slot:toc>\ntexto\n</x-ui::layout.docs>"))
        ->toContain('data-layout="docs"')
        ->toContain('Nesta página')
        ->toContain('href="#a"');
});
