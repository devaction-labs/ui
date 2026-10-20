<?php

use Illuminate\Support\Facades\Blade;

test('sidebar renders the mobile bar, overlay and collapsible nav', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::sidebar label="Menu do CRM">
            <x-slot:brand><x-ui::sidebar.brand name="Acme CRM" href="/" /></x-slot:brand>
            <x-ui::sidebar.search />
            <x-ui::sidebar.nav>
                <x-ui::sidebar.item icon="home-01" href="/" current>Painel</x-ui::sidebar.item>
                <x-ui::sidebar.item icon="user" href="/contatos" badge="3" wire:navigate>Contatos</x-ui::sidebar.item>
            </x-ui::sidebar.nav>
            <x-ui::sidebar.spacer />
            <x-ui::sidebar.profile name="Alex Nogueira" description="Admin" />
        </x-ui::sidebar>
        BLADE);

    expect($html)
        ->toContain('data-sidebar-root')
        ->toContain('md:hidden')
        ->toContain('$store.uiSidebar.close()')
        ->toContain('aria-label="Menu do CRM"')
        ->toContain('x-bind:data-collapsed="$store.uiSidebar.isCollapsed() ? \'\' : null"')
        ->toContain('md:w-[236px]')
        ->toContain('md:data-collapsed:w-16')
        ->toContain('md:sticky')
        ->toContain('data-sidebar-toggle')
        ->toContain('Acme CRM')
        ->toContain('data-sidebar-search')
        ->toMatch('/href="\/"[^>]*aria-current="page"/s')
        ->toMatch('/href="\/contatos"[^>]*wire:navigate/s')
        ->toContain('>3</span>')
        ->toContain('in-data-collapsed:sr-only')
        ->toContain('Alex Nogueira');
    expect(substr_count($html, 'data-sidebar-toggle'))->toBe(2);
});

test('sidebar groups can expand and collapse', function () {
    $html = Blade::render('<x-ui::sidebar.group heading="Relatórios" expandable :expanded="false"><x-ui::sidebar.item>Vendas</x-ui::sidebar.item></x-ui::sidebar.group>');

    expect($html)
        ->toContain('x-data="{ expanded: false }"')
        ->toContain('x-collapse')
        ->toContain('Relatórios')
        ->toContain('type="button"');
});

test('sidebar profile with slot becomes a menu', function () {
    expect(Blade::render('<x-ui::sidebar.profile name="Alex"><x-ui::menu.item>Sair</x-ui::menu.item></x-ui::sidebar.profile>'))
        ->toContain('data-dropdown')
        ->toContain('aria-label="Menu de Alex"')
        ->toContain('Sair');
});

test('main renders the panel with optional container', function () {
    expect(Blade::render('<x-ui::main container>Oi</x-ui::main>'))
        ->toContain('<main')
        ->toContain('bg-panel')
        ->toContain('max-w-7xl');
});
