<?php

use Illuminate\Support\Facades\Blade;

test('switch renders a native checkbox with switch role', function () {
    $html = Blade::render('<x-ui::switch wire:model.live="auto" label="Review automático" description="A IA revisa." checked />');

    expect($html)
        ->toContain('role="switch"')
        ->toContain('type="checkbox"')
        ->toContain('wire:model.live="auto"')
        ->toMatch('/\schecked[\s>="]/')
        ->toContain('h-6 w-10')
        ->toContain('peer-checked:bg-primary')
        ->toContain('peer-checked:translate-x-4')
        ->toContain('Review automático')
        ->toContain('A IA revisa.');
});

test('switch sizes and colors', function (string $attributes, string $expected) {
    expect(Blade::render("<x-ui::switch {$attributes} />"))->toContain($expected);
})->with([
    ['size="sm"', 'h-5 w-8'],
    ['size="lg"', 'h-7 w-12'],
    ['color="ai"', 'peer-checked:bg-ai'],
    ['color="ok"', 'peer-checked:bg-ok-fg'],
]);

test('switch loading shows a spinner and disables the input', function () {
    $html = Blade::render('<x-ui::switch aria-label="Salvando" loading />');

    expect($html)
        ->toContain('aria-busy="true"')
        ->toContain('data-spinner')
        ->toMatch('/role="switch"[^>]*\sdisabled[\s>]/s');
});

test('switch disabled renders a single disabled attribute', function () {
    $html = Blade::render('<x-ui::switch aria-label="x" disabled />');

    expect(preg_match_all('/\sdisabled(?=[\s>\/])/', $html))->toBe(1)->and($html)->toContain('opacity-50');
});

test('switch can place the label on the right', function () {
    $html = Blade::render('<x-ui::switch label="Tema" align="left" />');

    expect($html)->toMatch('/role="switch".*Tema/s');
});
