<?php

use DevAction\Ui\Support\BladeComponentParser;

$fixtures = __DIR__.'/../Fixtures/components';
$real = __DIR__.'/../../resources/views/components';

it('U1 lê componente sem @props', function () use ($fixtures) {
    $parsed = (new BladeComponentParser)->parse($fixtures.'/plain.blade.php', $fixtures);

    expect($parsed['name'])->toBe('plain')
        ->and($parsed['tag'])->toBe('x-ui::plain')
        ->and($parsed['file'])->toBe('plain.blade.php')
        ->and($parsed['props'])->toBe([])
        ->and($parsed['doc'])->toBe("Componente simples.\n\nSegunda linha.");
});

it('U2 converte defaults de @props sem executar PHP', function () use ($fixtures) {
    $props = (new BladeComponentParser)->parse($fixtures.'/props.blade.php', $fixtures)['props'];

    expect($props)->toBe([
        ['name' => 'a', 'attribute' => 'a', 'default' => null],
        ['name' => 'b', 'attribute' => 'b', 'default' => false],
        ['name' => 'variantBase', 'attribute' => 'variant-base', 'default' => 'base'],
        ['name' => 'd', 'attribute' => 'd', 'default' => '[]'],
        ['name' => 'e', 'attribute' => 'e', 'default' => "__('Sign in')"],
    ]);
});

it('U3 index vira o nome da pasta e lista filhos', function () use ($fixtures) {
    $parsed = (new BladeComponentParser)->parse($fixtures.'/group/index.blade.php', $fixtures);

    expect($parsed['name'])->toBe('group')
        ->and($parsed['children'])->toBe(['group.item']);
});

it('U4 aninha o nome com ponto', function () use ($real) {
    $parsed = (new BladeComponentParser)->parse($real.'/combobox/option/index.blade.php', $real);

    expect($parsed['name'])->toBe('combobox.option');
});

it('U5 lê o button real', function () use ($real) {
    $parsed = (new BladeComponentParser)->parse($real.'/button.blade.php', $real);

    expect($parsed['props'])->toContain(['name' => 'variant', 'attribute' => 'variant', 'default' => 'outline'])
        ->and(collect($parsed['props'])->firstWhere('name', 'iconTrailing')['attribute'])->toBe('icon-trailing')
        ->and($parsed['attributes'])->toContain('icon:trailing', 'tooltip:position', 'tooltip:kbd');
});

it('U6 acha slots no doc e em isset/isNotEmpty', function () use ($fixtures) {
    $parsed = (new BladeComponentParser)->parse($fixtures.'/slots.blade.php', $fixtures);

    expect($parsed['slots'])->toBe(['actions', 'footer'])
        ->and($parsed['attributes'])->toBe(['data-x', 'icon:trailing']);
});
