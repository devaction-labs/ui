<?php

use Illuminate\Support\Facades\Blade;

test('icon renders a stroke rounded svg hidden from assistive tech', function () {
    $html = Blade::render('<x-ui::icon name="tick-02" />');

    expect($html)
        ->toContain('viewBox="0 0 24 24"')
        ->toContain('stroke="currentColor"')
        ->toContain('stroke-width="1.5"')
        ->toContain('aria-hidden="true"')
        ->toContain('<path d="M5 14L8.5 17.5L19 6.5" />');
});

test('icon with a label is exposed as an image', function () {
    $html = Blade::render('<x-ui::icon name="github" label="GitHub" />');

    expect($html)
        ->toContain('role="img"')
        ->toContain('aria-label="GitHub"')
        ->not->toContain('aria-hidden');
});

test('badge and tag map status colors to tokens', function (string $component, string $color, string $expectedClass) {
    $html = Blade::render("<x-ui::{$component} :color=\"\$color\">Status</x-ui::{$component}>", ['color' => $color]);

    expect($html)->toContain($expectedClass);
})->with([
    ['badge', 'risk', 'bg-risk-bg'],
    ['badge', 'ok', 'bg-ok-bg'],
    ['badge', 'plain', 'bg-plain-bg'],
    ['tag', 'warn', 'bg-warn-bg'],
    ['tag', 'info', 'bg-info-bg'],
]);

test('badge can show a status dot', function () {
    expect(Blade::render('<x-ui::badge color="ok" dot>Em dia</x-ui::badge>'))
        ->toContain('rounded-full bg-current');
});

test('tag is uppercase mono', function () {
    expect(Blade::render('<x-ui::tag>Novo</x-ui::tag>'))
        ->toContain('font-mono')
        ->toContain('uppercase');
});

test('kbd renders a keyboard hint', function () {
    expect(Blade::render('<x-ui::kbd>⌘K</x-ui::kbd>'))
        ->toContain('<kbd')
        ->toContain('⌘K');
});

test('logo follows the theme tokens and accepts a wordmark', function () {
    $html = Blade::render('<x-ui::logo>DevAction UI</x-ui::logo>');

    expect($html)
        ->toContain('fill-card')
        ->toContain('fill-ai')
        ->toContain('DevAction UI');
});

test('icon only applies the default size when no size class is given', function () {
    expect(Blade::render('<x-ui::icon name="tick-02" />'))->toContain('class="shrink-0 size-5"');
    expect(Blade::render('<x-ui::icon name="tick-02" class="size-4 text-muted" />'))
        ->toContain('size-4 text-muted')
        ->not->toContain('size-5');
});
