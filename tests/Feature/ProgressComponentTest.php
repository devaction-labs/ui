<?php

use Illuminate\Support\Facades\Blade;

test('progress renders an accessible bar with the computed width', function () {
    $html = Blade::render('<x-ui::progress value="75" />');

    expect($html)
        ->toContain('role="progressbar"')
        ->toContain('aria-valuemin="0"')
        ->toContain('aria-valuemax="100"')
        ->toContain('aria-valuenow="75"')
        ->toContain('--progress: 75%')
        ->toContain('bg-primary h-full w-(--progress)')
        ->toContain('h-1.5');
});

test('progress uses max for non percentage scales and clamps the value', function () {
    expect(Blade::render('<x-ui::progress value="3" max="7" />'))->toContain('--progress: 42.86%');
    expect(Blade::render('<x-ui::progress value="120" />'))->toContain('--progress: 100%');
    expect(Blade::render('<x-ui::progress value="-5" />'))->toContain('--progress: 0%');
});

test('progress maps tailwind colors and sizes to tokens', function (string $attributes, string $expected) {
    expect(Blade::render("<x-ui::progress value=\"50\" {$attributes} />"))->toContain($expected);
})->with([
    ['color="amber"', 'bg-ai h-full'],
    ['color="green"', 'bg-ok-fg h-full'],
    ['color="red"', 'bg-missing h-full'],
    ['color="blue"', 'bg-learning h-full'],
    ['size="sm"', 'h-1'],
    ['size="lg"', 'h-2'],
]);

test('progress shows label, percentage, custom value and description', function () {
    $percentage = Blade::render('<x-ui::progress value="69" label="Escopo do M3" show-value description="18 de 26 itens" />');
    $custom = Blade::render('<x-ui::progress value="62" max="80" label="Banco de horas" value-label="62 h de 80 h" />');

    expect($percentage)
        ->toContain('aria-label="Escopo do M3"')
        ->toContain('>69%</span>')
        ->toContain('aria-valuetext="69%"')
        ->toContain('18 de 26 itens');

    expect($custom)
        ->toContain('62 h de 80 h')
        ->toContain('aria-valuetext="62 h de 80 h"');
});

test('segmented progress draws one pill per step and marks the current one', function () {
    $html = Blade::render('<x-ui::progress value="2" max="5" variant="segmented" current />');

    expect($html)
        ->toContain('grid-template-columns: repeat(5, minmax(0, 1fr))')
        ->toContain('aria-valuenow="2"');
    expect(substr_count($html, 'rounded-full bg-primary'))->toBe(2);
    expect(substr_count($html, 'rounded-full bg-ai'))->toBe(1);
    expect(substr_count($html, 'rounded-full bg-line'))->toBe(2);
});

test('stacked segments size themselves against the parent max', function () {
    $html = Blade::render('<x-ui::progress value="2" max="5"><x-ui::progress.segment value="1" color="green" /><x-ui::progress.segment value="1" color="amber" /></x-ui::progress>');

    expect($html)
        ->toContain('style="width: 20%" class="h-full rounded-full bg-ok-fg"')
        ->toContain('style="width: 20%" class="h-full rounded-full bg-ai"')
        ->toContain('aria-valuenow="2"');
});

test('indeterminate progress animates and omits the current value', function () {
    $html = Blade::render('<x-ui::progress label="Sincronizando" indeterminate />');

    expect($html)
        ->toContain('animate-indeterminate')
        ->toContain('motion-reduce:animate-none')
        ->not->toContain('aria-valuenow');
});
