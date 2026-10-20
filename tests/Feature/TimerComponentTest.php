<?php

use Illuminate\Support\Facades\Blade;

test('timer card renders the formatted time and controls', function () {
    $html = Blade::render('<x-ui::timer label="Corrigir N+1" :seconds="3725" x-on:timer-stopped="save()" />');

    expect($html)
        ->toContain('data-timer')
        ->toContain('uiTimer(')
        ->toContain('x-modelable="value"')
        ->toContain('role="timer"')
        ->toContain('01:02:05')
        ->toContain('PAUSADO')
        ->toContain('Corrigir N+1')
        ->toContain('aria-label="Parar e lançar"')
        ->toContain('x-on:timer-stopped="save()"')
        ->toContain('data-icon="stop"');
});

test('running timer starts as running', function () {
    expect(Blade::render('<x-ui::timer running :started-at="1760000000" />'))
        ->toContain('RODANDO')
        ->toContain('\u0022running\u0022:true')
        ->toContain('\u0022startedAt\u0022:1760000000');
});

test('mini timer renders a compact row', function () {
    $html = Blade::render('<x-ui::timer variant="mini" label="Mini" :seconds="1520" />');

    expect($html)
        ->toContain('format(false)')
        ->toContain('>00:25</span>')
        ->not->toContain('Parar e lançar');
});
