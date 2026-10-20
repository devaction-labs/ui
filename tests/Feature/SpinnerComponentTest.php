<?php

use Illuminate\Support\Facades\Blade;

test('spinner renders a decorative ring by default', function () {
    $html = Blade::render('<x-ui::spinner />');

    expect($html)
        ->toContain('data-spinner')
        ->toContain('aria-hidden="true"')
        ->toContain('size-[22px] border-[3px]')
        ->toContain('border-line border-t-ink')
        ->toContain('animate-spinner')
        ->not->toContain('role="status"');
});

test('spinner with a label is announced as status', function () {
    $html = Blade::render('<x-ui::spinner label="Carregando PRs" />');

    expect($html)
        ->toContain('role="status"')
        ->toContain('<span class="sr-only">Carregando PRs</span>')
        ->not->toContain('aria-hidden');
});

test('spinner dots render three staggered dots', function () {
    $html = Blade::render('<x-ui::spinner variant="dots" />');

    expect(substr_count($html, 'animate-blink'))->toBe(3);
    expect($html)
        ->toContain('[animation-delay:200ms]')
        ->toContain('[animation-delay:400ms]')
        ->toContain('motion-reduce:animate-none');
});

test('spinner supports sizes and colors', function (string $attributes, string $expected) {
    expect(Blade::render("<x-ui::spinner {$attributes} />"))->toContain($expected);
})->with([
    ['size="xs"', 'size-3.5 border-2'],
    ['size="sm"', 'size-4 border-2'],
    ['size="lg"', 'size-8 border-[3px]'],
    ['color="current"', 'border-current/25 border-t-current'],
    ['color="ai"', 'border-warn-line border-t-ai'],
]);

test('button uses the spinner component while loading', function () {
    $html = Blade::render('<x-ui::button size="sm" loading>Salvando</x-ui::button>');

    expect($html)
        ->toContain('group-data-loading/button:inline-flex')
        ->toContain('data-spinner')
        ->toContain('size-3.5 border-2')
        ->toContain('border-current/25 border-t-current');
});
