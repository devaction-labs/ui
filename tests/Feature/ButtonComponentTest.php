<?php

use Illuminate\Support\Facades\Blade;

test('button defaults to the outline variant and base size', function () {
    $html = Blade::render('<x-ui::button>Cancelar</x-ui::button>');

    expect($html)
        ->toContain('<button')
        ->toContain('type="button"')
        ->toContain('border-line bg-card')
        ->toContain('h-9.5');
});

test('button maps each variant to tokens', function (string $variant, string $expectedClass) {
    $html = Blade::render('<x-ui::button :variant="$variant">Ação</x-ui::button>', ['variant' => $variant]);

    expect($html)->toContain($expectedClass);
})->with([
    ['primary', 'bg-ink text-on-ink'],
    ['filled', 'bg-wrap text-ink'],
    ['danger', 'bg-risk-bg text-risk-fg'],
    ['ghost', 'text-ink-2'],
    ['subtle', 'text-muted'],
    ['ai', 'bg-warn-bg text-warn-fg'],
    ['info', 'bg-info-bg text-info-fg'],
]);

test('button supports the design sizes', function (string $size, string $expectedHeight) {
    $html = Blade::render('<x-ui::button :size="$size">Ação</x-ui::button>', ['size' => $size]);

    expect($html)->toContain($expectedHeight);
})->with([
    ['xs', 'h-6'],
    ['sm', 'h-8'],
    ['base', 'h-9.5'],
    ['lg', 'h-11.5'],
]);

test('button renders a link when href is given', function () {
    $html = Blade::render('<x-ui::button variant="primary" icon:trailing="arrow-right-01" href="/docs">Começar</x-ui::button>');

    expect($html)
        ->toContain('<a')
        ->toContain('href="/docs"')
        ->toContain('data-icon="arrow-right-01"')
        ->not->toContain('<button')
        ->not->toContain('icon:trailing');
});

test('icon only button becomes square and keeps the aria label', function () {
    $html = Blade::render('<x-ui::button icon="more-horizontal" aria-label="Mais ações" />');

    expect($html)
        ->toContain('aria-label="Mais ações"')
        ->toContain('w-9.5')
        ->not->toContain('px-3.5');
});

test('loading button shows a spinner, is busy and blocks clicks', function () {
    $html = Blade::render('<x-ui::button variant="primary" icon="sent" loading>Publicando</x-ui::button>');

    expect($html)
        ->toContain('data-loading')
        ->toContain('aria-busy="true"')
        ->toMatch('/\sdisabled[\s>]/')
        ->toContain('animate-spin')
        ->toContain('group-data-loading/button:hidden');
});

test('button reacts to livewire loading automatically unless disabled', function () {
    $automatic = Blade::render('<x-ui::button wire:click="save">Salvar</x-ui::button>');
    $optedOut = Blade::render('<x-ui::button wire:click="save" :loading="false">Salvar</x-ui::button>');

    expect($automatic)->toContain('wire:click="save"')->toContain('animate-spin')->not->toContain('aria-busy');
    expect($optedOut)->not->toContain('animate-spin');
});

test('disabled link is removed from the tab order', function () {
    $html = Blade::render('<x-ui::button href="/docs" disabled>Docs</x-ui::button>');

    expect($html)
        ->toContain('aria-disabled="true"')
        ->toContain('tabindex="-1"');
});

test('inset pulls the button out with negative margins', function () {
    $html = Blade::render('<x-ui::button variant="ghost" inset="top bottom">Editar</x-ui::button>');

    expect($html)->toContain('-mt-2')->toContain('-mb-2');
});
