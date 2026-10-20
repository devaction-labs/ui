<?php

use Illuminate\Support\Facades\Blade;

test('hover card opens on hover or focus and floats next to the trigger', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::hover-card position="top" align="center" delay="300">
            <a href="/pessoas/alex">Alex</a>
            <x-slot:card class="w-80">
                <x-ui::hover-card.person name="Alex" description="Dev" online :stats="['PRs abertos' => 4]" />
            </x-slot:card>
        </x-ui::hover-card>
        BLADE);

    expect($html)
        ->toContain('data-hover-card')
        ->toContain('uiHoverCard({ delay: 300 })')
        ->toContain('x-on:focusin="show()"')
        ->toContain('x-on:keydown.escape="hide()"')
        ->toContain("x-ui-float=\"{ placement: 'top', align: 'center', gap: 8 }\"")
        ->toContain('w-80')
        ->toContain('Dev')
        ->toContain('bg-ok-fg')
        ->toContain('PRs abertos');
});
