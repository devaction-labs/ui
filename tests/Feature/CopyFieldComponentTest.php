<?php

use Illuminate\Support\Facades\Blade;

test('copy field masks secrets keeping the prefix and last characters', function () {
    $html = Blade::render('<x-ui::copy-field label="Segredo" value="whsec_9f3c1e7a2b8d4f60c5e1" masked description="Só uma vez." />');

    expect($html)
        ->toContain('data-copy-field')
        ->toContain('>whsec_••••••••••••••••c5e1</output>')
        ->toContain('aria-label="Mostrar"')
        ->toContain('aria-describedby=')
        ->toContain('aria-live="polite"')
        ->toContain('Só uma vez.');
});

test('copy field without mask shows the whole value', function () {
    $html = Blade::render('<x-ui::copy-field value="https://acme.test/convite/8KQ2" />');

    expect($html)
        ->toContain('>https://acme.test/convite/8KQ2</output>')
        ->not->toContain('aria-label="Mostrar"');
});
