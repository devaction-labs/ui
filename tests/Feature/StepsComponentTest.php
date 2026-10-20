<?php

use Illuminate\Support\Facades\Blade;

test('steps render a vertical ordered list with states', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::steps>
            <x-ui::steps.item state="done" label="Conta criada" description="agora" />
            <x-ui::steps.item state="current" label="Primeiro projeto"><button>Criar</button></x-ui::steps.item>
            <x-ui::steps.item state="error" label="Pagamento" />
            <x-ui::steps.item label="Convidar o time" />
        </x-ui::steps>
        BLADE);

    expect($html)
        ->toContain('data-steps')
        ->toContain('<ol')
        ->toContain('data-state="done"')
        ->toContain('Feito:')
        ->toMatch('/data-state="current"\s+aria-current="step"/')
        ->toContain('<button>Criar</button>')
        ->toContain('Com erro:')
        ->toContain('data-state="todo"')
        ->toContain('group-last/step:invisible')
        ->toContain('agora');
});
