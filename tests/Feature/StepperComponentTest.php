<?php

use Illuminate\Support\Facades\Blade;

test('stepper derives done, current and todo states from current', function () {
    $html = Blade::render('<x-ui::stepper :current="2" :steps="$steps" />', ['steps' => [
        ['label' => 'Cliente', 'description' => 'quem contrata'],
        ['label' => 'Escopo'],
        ['label' => 'Valores'],
    ]]);

    expect($html)
        ->toContain('data-stepper')
        ->toContain('Passo 2 de 3')
        ->toContain('grid-template-columns: repeat(3, minmax(0, 1fr))')
        ->toContain('data-state="done"')
        ->toMatch('/data-state="current"\s+aria-current="step"/')
        ->toContain('data-state="todo"')
        ->toContain('Feito:')
        ->toContain('quem contrata')
        ->toContain('bg-ai');
});

test('stepper marks the current step as failed', function () {
    $html = Blade::render('<x-ui::stepper :current="1" error :counter="false" :steps="[[\'label\' => \'Cliente\']]"><p>slot</p></x-ui::stepper>');

    expect($html)
        ->toContain('data-state="error"')
        ->toContain('falhou · tente de novo')
        ->toContain('<p>slot</p>')
        ->not->toContain('Passo 1 de 1');
});
