<?php

use Illuminate\Support\Facades\Blade;

test('cookie consent offers accept all, necessary only and per category choices', function () {
    $html = Blade::render('<x-ui::cookie-consent policy="/privacidade" />');

    expect($html)
        ->toContain('data-cookie-consent')
        ->toContain('aria-label="Aviso de cookies"')
        ->toContain('uiCookieConsent({ categories: JSON.parse(')
        ->toContain('\\u0022analytics\\u0022,\\u0022marketing\\u0022')
        ->toContain('force: false })')
        ->toContain('Só necessários')
        ->toContain('Aceitar todos')
        ->toContain('Salvar escolha')
        ->toContain('x-model="choices.analytics"')
        ->toContain('aria-label="Necessários (sempre ligados)"')
        ->toContain('href="/privacidade"')
        ->toContain('fixed inset-x-4 bottom-4');
});

test('cookie consent accepts custom categories and inline placement', function () {
    $html = Blade::render('<x-ui::cookie-consent :fixed="false" force :categories="[\'support\' => [\'Suporte\', \'Chat de ajuda.\']]">Texto próprio.</x-ui::cookie-consent>');

    expect($html)
        ->toContain('\\u0022support\\u0022')
        ->toContain('force: true })')
        ->toContain('Chat de ajuda.')
        ->toContain('Texto próprio.')
        ->not->toContain('fixed inset-x-4');
});
