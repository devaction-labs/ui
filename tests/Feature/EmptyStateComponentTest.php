<?php

use Illuminate\Support\Facades\Blade;

test('card empty state renders the icon tile, grid, label, heading, text and actions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::empty-state icon="folder-add" label="Projetos" heading="Seu primeiro projeto" text="Comece por aqui.">
            <x-slot:actions>
                <x-ui::button variant="primary">Criar projeto</x-ui::button>
            </x-slot:actions>
        </x-ui::empty-state>
        BLADE);

    expect($html)
        ->toContain('data-empty-state')
        ->toContain('rounded-card border border-line bg-card')
        ->toContain('bg-grid-sm mask-fade-card')
        ->toContain('size-14 rounded-2xl')
        ->toContain('data-icon="folder-add"')
        ->toContain('Projetos')
        ->toContain('<h3')
        ->toContain('Seu primeiro projeto')
        ->toContain('Comece por aqui.')
        ->toContain('Criar projeto');
});

test('dashed empty state is compact and has no grid', function () {
    $html = Blade::render('<x-ui::empty-state variant="dashed" icon="search-01" heading="Nada encontrado" text="Tente outro termo." />');

    expect($html)
        ->toContain('border-dashed border-line-hover')
        ->toContain('size-10 rounded-xl')
        ->toContain('text-sm font-semibold')
        ->not->toContain('bg-grid-sm');
});

test('plain empty state has no border', function () {
    $html = Blade::render('<x-ui::empty-state variant="plain" heading="Sem itens" />');

    expect($html)->not->toContain('border-dashed')->not->toContain('rounded-card');
});

test('ai color paints the icon tile amber', function () {
    $html = Blade::render('<x-ui::empty-state color="ai" icon="sparkles" heading="Sua trilha aparece depois" />');

    expect($html)->toContain('border-warn-line bg-warn-bg text-accent');
});

test('default slot adds extra content below the text', function () {
    $html = Blade::render('<x-ui::empty-state variant="dashed" heading="Nada">Tente <a href="#">limpar os filtros</a>.</x-ui::empty-state>');

    expect($html)->toContain('<a href="#">limpar os filtros</a>');
});

test('empty state omits optional parts when not given', function () {
    $html = Blade::render('<x-ui::empty-state heading="Só o título" />');

    expect($html)
        ->toContain('Só o título')
        ->not->toContain('data-icon=')
        ->not->toContain('<p');
});
