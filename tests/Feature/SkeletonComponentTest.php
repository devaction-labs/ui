<?php

use Illuminate\Support\Facades\Blade;

test('skeleton renders a hidden placeholder block without animation by default', function () {
    $html = Blade::render('<x-ui::skeleton class="h-10 w-full" />');

    expect($html)
        ->toContain('data-skeleton')
        ->toContain('aria-hidden="true"')
        ->toContain('rounded-md bg-line-strong')
        ->not->toContain('animate-shimmer')
        ->not->toContain('animate-pulse');
});

test('skeleton keeps a custom radius instead of the default one', function () {
    expect(Blade::render('<x-ui::skeleton class="size-9 rounded-full" />'))
        ->toContain('rounded-full')
        ->not->toContain('rounded-md');
});

test('skeleton supports shimmer and pulse and stops with reduced motion', function (string $animate, string $expected) {
    $html = Blade::render('<x-ui::skeleton :animate="$animate" />', ['animate' => $animate]);

    expect($html)
        ->toContain($expected)
        ->toContain('motion-reduce:animate-none')
        ->not->toContain('animate="');
})->with([
    ['shimmer', 'skeleton-shimmer animate-shimmer'],
    ['pulse', 'animate-pulse'],
]);

test('skeleton line has base and large sizes', function () {
    expect(Blade::render('<x-ui::skeleton.line />'))->toContain('data-skeleton-line')->toContain('h-3');
    expect(Blade::render('<x-ui::skeleton.line size="lg" />'))->toContain('h-4');
});

test('skeleton group passes the animation down and announces its label', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::skeleton.group animate="shimmer" label="Carregando PRs">
            <x-ui::skeleton class="size-9 rounded-full" />
            <x-ui::skeleton.line />
        </x-ui::skeleton.group>
        BLADE);

    expect($html)
        ->toContain('data-skeleton-group')
        ->toContain('role="status" aria-busy="true"')
        ->toContain('<span class="sr-only">Carregando PRs</span>');
    expect(substr_count($html, 'skeleton-shimmer animate-shimmer'))->toBe(2);
});

test('skeleton group without label has no status role', function () {
    expect(Blade::render('<x-ui::skeleton.group><x-ui::skeleton.line /></x-ui::skeleton.group>'))
        ->not->toContain('role="status"');
});

test('skeleton line only fills the width when no width class is given', function () {
    expect(Blade::render('<x-ui::skeleton.line />'))->toContain('w-full');
    expect(Blade::render('<x-ui::skeleton.line class="w-1/2" />'))
        ->toContain('w-1/2')
        ->not->toMatch('/data-skeleton-line[^>]*w-full/');
});
