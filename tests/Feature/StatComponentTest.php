<?php

use Illuminate\Support\Facades\Blade;

test('stat renders label, value, unit, trend and description in a card', function () {
    $html = Blade::render('<x-ui::stat label="Pronto pra entregar" value="78,4" unit="%" trend="+6 pts" description="desde segunda" />');

    expect($html)
        ->toContain('data-stat')
        ->toContain('border border-line bg-card')
        ->toContain('font-mono text-xs tracking-[.08em] uppercase')
        ->toContain('Pronto pra entregar')
        ->toContain('text-[44px]')
        ->toContain('78,4<span class="ml-0.5 text-muted text-2xl">%</span>')
        ->toContain('desde segunda');
});

test('trend direction comes from the sign and picks the color', function (string $trend, string $icon, string $color) {
    $html = Blade::render('<x-ui::stat value="1" :trend="$trend" />', ['trend' => $trend]);

    expect($html)
        ->toContain("data-icon=\"{$icon}\"")
        ->toContain($color);
})->with([
    ['+6 pts', 'arrow-up-01', 'bg-ok-bg'],
    ['-8 h', 'arrow-down-01', 'bg-risk-bg'],
]);

test('inverse trend makes going down good', function () {
    $html = Blade::render('<x-ui::stat value="17" trend="-3" trend:inverse />');

    expect($html)
        ->toContain('data-icon="arrow-down-01"')
        ->toContain('bg-ok-bg')
        ->not->toContain('trend:inverse');
});

test('trend without sign is neutral unless a direction is forced', function () {
    expect(Blade::render('<x-ui::stat value="4" trend="estável" />'))
        ->toContain('bg-plain-bg')
        ->not->toContain('data-icon="arrow-');
    expect(Blade::render('<x-ui::stat value="4" trend="12%" trend:direction="up" />'))
        ->toContain('data-icon="arrow-up-01"');
});

test('stat colors the value and supports the inverted highlight', function () {
    expect(Blade::render('<x-ui::stat value="29" color="ai" />'))->toContain('text-accent');
    expect(Blade::render('<x-ui::stat value="17" color="risk" />'))->toContain('text-risk-fg');
    expect(Blade::render('<x-ui::stat label="Destaque" value="4" variant="inverted" />'))
        ->toContain('bg-ink')
        ->toContain('text-on-ink/70');
});

test('small stat keeps the label first in the DOM but shows it below', function () {
    $html = Blade::render('<x-ui::stat value="58" label="componentes" size="sm" />');

    expect($html)->toContain('text-[28px]')->toContain('order-last');
    expect($html)->toMatch('/componentes.*58/s');
});

test('stat group lays out columns and the divided strip', function () {
    $grid = Blade::render('<x-ui::stat.group columns="3"><x-ui::stat value="1" /></x-ui::stat.group>');
    $strip = Blade::render('<x-ui::stat.group columns="5" divided><x-ui::stat value="1" size="sm" /></x-ui::stat.group>');

    expect($grid)->toContain('grid grid-cols-2 sm:grid-cols-3')->toContain('gap-3.5');
    expect($strip)
        ->toContain('md:grid-cols-5')
        ->toContain('md:*:not-last:border-r')
        ->toContain('px-[22px] py-5')
        ->not->toContain('rounded-[14px] p-5');
});

test('tag supports a small size', function () {
    expect(Blade::render('<x-ui::tag size="sm">+6 pts</x-ui::tag>'))
        ->toContain('px-[7px] py-[3px] text-[11px]')
        ->not->toContain('px-[9px]');
});
