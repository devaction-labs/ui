<?php

use Illuminate\Support\Facades\Blade;

test('ai prompt renders an autogrowing textarea with toolbar and send button', function () {
    $html = Blade::render('<x-ui::ai-prompt wire:model="prompt" submit="send" stop="cancel" busy-property="isResponding" max="4000"><x-slot:toolbar><span>Revisor</span></x-slot:toolbar></x-ui::ai-prompt>');

    expect($html)
        ->toContain('data-ai-prompt')
        ->toContain('uiPrompt(')
        ->toContain('\u0022submit\u0022:\u0022send\u0022')
        ->toContain('\u0022busyProperty\u0022:\u0022isResponding\u0022')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model="prompt"')
        ->toContain('<textarea')
        ->toContain('x-on:keydown="keydown($event)"')
        ->toContain('aria-label="Anexar"')
        ->toContain('aria-label="Comandos"')
        ->toContain('aria-label="Mencionar"')
        ->toContain('<span>Revisor</span>')
        ->toContain('x-text="counter()"')
        ->toContain("isBusy() ? 'Parar resposta' : 'Enviar'");
});

test('ai prompt can hide buttons and show attachments', function () {
    $html = Blade::render('<x-ui::ai-prompt :attach="false" :commands="false" :mentions="false"><x-slot:attachments><span>PDF</span></x-slot:attachments></x-ui::ai-prompt>');

    expect($html)
        ->toContain('<span>PDF</span>')
        ->not->toContain('aria-label="Anexar"')
        ->not->toContain('x-text="counter()"');
});
