<?php

use Illuminate\Support\Facades\Blade;

test('aura spins a conic gradient around its content', function () {
    $html = Blade::render('<x-ui::aura size="lg" radius="12px"><button>Oi</button></x-ui::aura>');

    expect($html)
        ->toContain('data-aura')
        ->toContain('data-variant="spin"')
        ->toContain('padding: 2.5px; border-radius: calc(12px + 2.5px)')
        ->toContain('motion-safe:animate-aura-spin')
        ->toContain('conic-gradient(from 0deg, transparent 0 62%, #D29A1E 80%')
        ->toContain('<button>Oi</button>');
});

test('aura dual, glow and inactive variants', function () {
    expect(Blade::render('<x-ui::aura variant="dual">x</x-ui::aura>'))->toContain('#2563EB 72%');
    expect(Blade::render('<x-ui::aura variant="glow" color="risk">x</x-ui::aura>'))
        ->toContain('motion-safe:animate-aura-glow')
        ->toContain('--aura-color: #C628288C');
    expect(Blade::render('<x-ui::aura :active="false">x</x-ui::aura>'))->not->toContain('animate-aura-spin');
});
