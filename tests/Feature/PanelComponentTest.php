<?php

use Illuminate\Support\Facades\Blade;

test('panel wraps a card with heading, description, actions, body and footer', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::panel heading="Repositórios" description="A IA revisa todo PR aberto.">
            <x-slot:actions><x-ui::button size="sm">Adicionar</x-ui::button></x-slot:actions>
            Corpo do painel
            <x-slot:footer><x-ui::button size="sm" variant="primary">Salvar</x-ui::button></x-slot:footer>
        </x-ui::panel>
        BLADE);

    expect($html)
        ->toContain('data-card-wrap')
        ->toContain('rounded-wrap bg-wrap p-1.5')
        ->toContain('<h2')
        ->toContain('Repositórios')
        ->toContain('A IA revisa todo PR aberto.')
        ->toContain('data-card-actions')
        ->toContain('Corpo do painel')
        ->toContain('border-t border-line-soft')
        ->toContain('Salvar');
});

test('panel can drop the frame, use a mono description and a flush body', function () {
    $html = Blade::render('<x-ui::panel heading="Tarefas" description=".data_table" mono :wrap="false" flush>Tabela</x-ui::panel>');

    expect($html)
        ->not->toContain('data-card-wrap')
        ->toContain('font-mono text-[11px]')
        ->toContain('-mx-[22px]');
});

test('panel without header parts renders only the body', function () {
    $html = Blade::render('<x-ui::panel>Só o corpo</x-ui::panel>');

    expect($html)->toContain('Só o corpo')->not->toContain('<h2')->not->toContain('data-card-actions');
});

test('card defaults to a bordered surface without frame', function () {
    $html = Blade::render('<x-ui::card>Conteúdo</x-ui::card>');

    expect($html)
        ->toContain('data-card')
        ->toContain('border border-line bg-card')
        ->toContain('p-[22px] rounded-card')
        ->not->toContain('data-card-wrap');
});

test('card supports sizes and the soft variant', function (string $size, string $expected) {
    $html = Blade::render('<x-ui::card :size="$size">x</x-ui::card>', ['size' => $size]);

    expect($html)->toContain($expected);
})->with([
    ['xs', 'p-3 rounded-xl'],
    ['sm', 'p-4 rounded-[14px]'],
    ['lg', 'p-8 rounded-[20px]'],
]);

test('soft card has no border', function () {
    expect(Blade::render('<x-ui::card variant="soft">x</x-ui::card>'))
        ->toContain('bg-wrap')
        ->not->toContain('border border-line');
});

test('card parts compose a header with heading level and size', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::card size="sm">
            <x-ui::card.header>
                <x-ui::card.heading size="lg" :level="3">Segurança</x-ui::card.heading>
                <x-ui::card.subheading>Alterada há 3 meses</x-ui::card.subheading>
                <x-ui::card.actions><x-ui::button size="sm">Alterar</x-ui::button></x-ui::card.actions>
            </x-ui::card.header>
            <x-ui::card.body flush>Lista</x-ui::card.body>
        </x-ui::card>
        BLADE);

    expect($html)
        ->toContain('<h3')
        ->toContain('text-xl')
        ->toContain('Alterada há 3 meses')
        ->toContain('-mx-4');
});
