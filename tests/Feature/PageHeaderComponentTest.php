<?php

use Illuminate\Support\Facades\Blade;

test('page header renders breadcrumbs, status, heading, actions and tabs', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::page-header heading="SGE · Gestão Eclesial" description="M3 vence sexta">
            <x-slot:breadcrumbs>
                <x-ui::breadcrumbs.item href="/projetos">Projetos</x-ui::breadcrumbs.item>
                <x-ui::breadcrumbs.item>SGE</x-ui::breadcrumbs.item>
            </x-slot:breadcrumbs>
            <x-slot:status><span>ATIVO</span></x-slot:status>
            <x-slot:actions><button>Nova tarefa</button></x-slot:actions>
            <x-slot:tabs><div role="tablist">abas</div></x-slot:tabs>
        </x-ui::page-header>
        BLADE);

    expect($html)
        ->toContain('data-page-header')
        ->toContain('aria-label="Breadcrumb"')
        ->toContain('href="/projetos"')
        ->toContain('<span>ATIVO</span>')
        ->toMatch('/<h1[^>]*text-\[32px\][^>]*>SGE · Gestão Eclesial<\/h1>/s')
        ->toContain('M3 vence sexta')
        ->toContain('<button>Nova tarefa</button>')
        ->toContain('role="tablist"');
});

test('small page header without optional parts', function () {
    $html = Blade::render('<x-ui::page-header heading="Contatos" size="sm" />');

    expect($html)
        ->toContain('text-2xl')
        ->not->toContain('Breadcrumb')
        ->not->toContain('<p');
});
