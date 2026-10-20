<?php

use Illuminate\Support\Facades\Blade;

test('banner variants map to tokens, icons and roles', function (string $variant, string $classes, string $icon, string $role) {
    $html = Blade::render('<x-ui::banner :variant="$variant" heading="Aviso." text="Texto." />', ['variant' => $variant]);

    expect($html)
        ->toContain($classes)
        ->toContain('data-icon="'.$icon.'"')
        ->toContain('role="'.$role.'"')
        ->toContain('<strong class="font-semibold">Aviso.</strong>');
})->with([
    ['info', 'border-info-line bg-info-bg text-info-fg', 'information-circle', 'status'],
    ['warning', 'border-warn-line bg-warn-bg text-warn-fg', 'alert-02', 'status'],
    ['danger', 'border-risk-line bg-risk-bg text-risk-fg', 'alert-diamond', 'alert'],
    ['offline', 'border-ink bg-ink text-on-ink', 'refresh', 'status'],
]);

test('banner action is a link with href or a button carrying wire attributes', function () {
    expect(Blade::render('<x-ui::banner heading="A" action="Ver" href="/changelog" />'))
        ->toContain('<a href="/changelog"');

    $html = Blade::render('<x-ui::banner heading="A" action="Renovar" wire:click="renew" dismissible class="mt-2" />');

    expect($html)
        ->toMatch('/<button type="button"[^>]*wire:click="renew"/')
        ->toContain('aria-label="Fechar aviso"')
        ->toContain("\$dispatch('banner-dismissed')")
        ->toMatch('/data-banner[^>]*mt-2/');
});
