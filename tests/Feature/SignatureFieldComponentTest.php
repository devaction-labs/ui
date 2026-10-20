<?php

use Illuminate\Support\Facades\Blade;

test('signature field renders draw and type modes', function () {
    $html = Blade::render('<x-ui::signature-field label="Assinatura" wire:model="signature" note="Guardamos o hash." />');

    expect($html)
        ->toContain('data-signature-field')
        ->toContain("uiSignature({ mode: 'draw', name: '' })")
        ->toContain('x-modelable="value"')
        ->toContain('wire:model="signature"')
        ->toContain('role="radiogroup"')
        ->toContain('Desenhar')
        ->toContain('Digitar')
        ->toContain('x-on:pointerdown="down($event)"')
        ->toContain('touch-none')
        ->toContain('Nome completo')
        ->toContain('data-icon="eraser"')
        ->toContain('Guardamos o hash.');
});

test('signature field can start typed with a hidden input', function () {
    expect(Blade::render('<x-ui::signature-field mode="type" signer="Ana Souza" name="signature" />'))
        ->toContain("uiSignature({ mode: 'type', name: 'Ana Souza' })")
        ->toContain('name="signature"');
});

test('signature field shows errors', function () {
    expect(Blade::render('<x-ui::signature-field error="Assine para continuar" />'))
        ->toContain('Assine para continuar')
        ->toContain('border-missing');
});
