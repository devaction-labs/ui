<?php

use Illuminate\Support\Facades\Blade;

test('pricing toggles billing and renders plans with prices, features and actions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::pricing yearly-badge="2 meses grátis" wire:model="yearly">
            <x-ui::pricing.plan name="Freelancer" :price="['monthly' => 'R$ 49', 'yearly' => 'R$ 39']" :features="['Review por IA']" cta="Começar" href="/cadastro" />
            <x-ui::pricing.plan name="Agência" featured tag="Mais usado" :price="['monthly' => 'R$ 199', 'yearly' => 'R$ 159']" cta="Assinar" wire:click="choose('agencia')" />
            <x-ui::pricing.plan name="Sob medida" price="Fale com a gente" cta="Falar com vendas" />
        </x-ui::pricing>
        BLADE);

    expect($html)
        ->toContain('data-pricing')
        ->toContain('x-modelable="yearly"')
        ->toContain('wire:model="yearly"')
        ->toContain('role="radiogroup"')
        ->toContain('2 meses grátis')
        ->toContain('>R$ 39</span>')
        ->toContain('cobrado anualmente')
        ->toContain('Review por IA')
        ->toContain('href="/cadastro"')
        ->toContain('border-2 border-ink')
        ->toContain('Mais usado')
        ->toContain('wire:click="choose(\'agencia\')"')
        ->toContain('Fale com a gente')
        ->and(substr_count($html, 'data-pricing-plan'))->toBe(3);
});
