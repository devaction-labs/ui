<?php

use Illuminate\Support\Facades\Blade;

test('avatar generates initials from the first and last name and reads the full name', function () {
    $html = Blade::render('<x-ui::avatar name="Alex da Silva Nogueira" />');

    expect($html)
        ->toContain('data-avatar')
        ->toContain('>AN</span>')
        ->toContain('<span class="sr-only">Alex da Silva Nogueira</span>')
        ->toContain('size-10')
        ->toContain('rounded-[11px]');
});

test('avatar uses a single initial for one word and custom initials when given', function () {
    expect(Blade::render('<x-ui::avatar name="DevAction" />'))->toContain('>D</span>');
    expect(Blade::render('<x-ui::avatar name="DevAction" initials="DA" />'))->toContain('>DA</span>');
});

test('avatar supports the design sizes and circle shape', function (string $size, string $expected) {
    $html = Blade::render('<x-ui::avatar name="Ana" :size="$size" circle />', ['size' => $size]);

    expect($html)->toContain($expected)->toContain('rounded-full');
})->with([
    ['xs', 'size-6'],
    ['sm', 'size-8'],
    ['base', 'size-10'],
    ['lg', 'size-14'],
    ['xl', 'size-18'],
]);

test('avatar maps tailwind color names to theme tokens', function (string $color, string $expected) {
    $html = Blade::render('<x-ui::avatar name="Ana" :color="$color" />', ['color' => $color]);

    expect($html)->toContain($expected);
})->with([
    ['red', 'bg-risk-bg text-risk-fg'],
    ['amber', 'bg-warn-bg text-warn-fg'],
    ['emerald', 'bg-ok-bg text-ok-fg'],
    ['blue', 'bg-info-bg text-info-fg'],
    ['indigo', 'bg-indigo-bg text-indigo-fg'],
    ['ink', 'bg-ink text-on-ink'],
    ['ai', 'border-ai bg-warn-bg'],
    ['outline', 'border-dashed'],
]);

test('auto color is stable for the same seed', function () {
    $first = Blade::render('<x-ui::avatar name="Carlos André" color="auto" />');
    $second = Blade::render('<x-ui::avatar name="Carlos André" color="auto" />');
    $seeded = Blade::render('<x-ui::avatar name="Outro Nome" color="auto" color:seed="Carlos André" />');

    $toneOf = fn (string $html): string => str($html)->match('/bg-(risk|warn|ok|info|indigo)-bg/')->value();

    expect($toneOf($first))->not->toBeEmpty()->toBe($toneOf($second))->toBe($toneOf($seeded));
    expect($seeded)->not->toContain('color:seed');
});

test('avatar renders an image, an icon or the default user icon', function () {
    expect(Blade::render('<x-ui::avatar src="/alex.jpg" name="Alex" />'))->toContain('<img src="/alex.jpg" alt="Alex"');
    expect(Blade::render('<x-ui::avatar icon="github" color="ink" />'))->toContain('data-icon="github"');
    expect(Blade::render('<x-ui::avatar />'))->toContain('data-icon="user"');
});

test('status badge is a labelled dot', function () {
    $html = Blade::render('<x-ui::avatar name="Ana" circle badge badge:color="green" badge:label="Online" />');

    expect($html)
        ->toContain('role="img" aria-label="Online"')
        ->toContain('bg-ok-fg')
        ->toContain('size-3 rounded-full')
        ->not->toContain('badge:color');
});

test('counter badge shows its content at the chosen position', function () {
    $html = Blade::render('<x-ui::avatar name="Ana" badge="3" badge:color="red" badge:position="top right" badge:circle />');

    expect($html)
        ->toContain('>3</span>')
        ->toContain('-top-0.5 -right-0.5')
        ->toContain('bg-missing')
        ->toContain('rounded-full');
});

test('avatar renders as a link or button when interactive', function () {
    expect(Blade::render('<x-ui::avatar name="Ana" href="/perfil" />'))->toContain('<a')->toContain('href="/perfil"');
    expect(Blade::render('<x-ui::avatar icon="user-add-01" as="button" aria-label="Convidar" />'))
        ->toContain('<button')
        ->toContain('type="button"')
        ->toContain('aria-label="Convidar"');
});

test('tooltip wraps the avatar in the tooltip component using the name', function () {
    $html = Blade::render('<x-ui::avatar name="Carlos André" tooltip tooltip:position="right" />');

    expect($html)
        ->toContain('data-tooltip')
        ->toContain('role="tooltip"')
        ->toContain('left-full')
        ->not->toContain('title=')
        ->not->toContain('tooltip:position');
});

test('avatar group overlaps avatars with a ring', function () {
    $html = Blade::render('<x-ui::avatar.group><x-ui::avatar name="Ana" /><x-ui::avatar name="Bia" /></x-ui::avatar.group>');

    expect($html)
        ->toContain('data-avatar-group')
        ->toContain('-space-x-2 *:ring-2 *:ring-card');
});

test('avatar group collapses extra people into a +N avatar with a label', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::avatar.group max="2" label="Revisores do #214">
            <x-ui::avatar name="Alex Nogueira" size="sm" circle />
            <x-ui::avatar name="Carlos André" size="sm" circle />
            <x-ui::avatar name="Fernanda Paiva" size="sm" circle />
            <x-ui::avatar name="Diego Rocha" size="sm" circle />
        </x-ui::avatar.group>
        BLADE);

    expect($html)
        ->toContain('+2')
        ->toContain('aria-label="2 pessoas a mais"')
        ->toContain('.slice(2, 4)')
        ->toContain('Revisores do #214')
        ->toContain('-space-x-2');

    expect(Blade::render('<x-ui::avatar.group><x-ui::avatar name="Ana" /></x-ui::avatar.group>'))
        ->not->toContain('x-init')
        ->not->toContain('pessoas a mais');
});
