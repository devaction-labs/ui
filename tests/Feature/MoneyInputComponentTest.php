<?php

use Illuminate\Support\Facades\Blade;

test('money input formats in the visible field and keeps the raw value hidden', function () {
    $html = Blade::render('<x-ui::money-input label="Valor do marco" wire:model="amount" value="18500" suffix="por marco" />');

    expect($html)
        ->toContain('data-money-input')
        ->toContain("x-data=\"uiMoney({ locale: 'pt-BR', decimals: 2, value: '18500' })\"")
        ->toContain('>R$</span>')
        ->toContain('inputmode="numeric"')
        ->toContain('x-on:input="typed($event)"')
        ->toContain('placeholder="0,00"')
        ->toContain('aria-label="Valor do marco em R$"')
        ->toMatch('/type="hidden"[^>]*x-ref="raw"[^>]*wire:model="amount"/s')
        ->toContain('por marco');
});

test('money input supports other currencies', function () {
    expect(Blade::render('<x-ui::money-input currency="USD" locale="en-US" />'))
        ->toContain('>US$</span>')
        ->toContain("locale: 'en-US'");
    expect(Blade::render('<x-ui::money-input symbol="₿" />'))->toContain('>₿</span>');
});

test('money input shows errors and keeps name on the hidden input', function () {
    $html = Blade::render('<x-ui::money-input name="discount" error="Valor alto demais" />');

    expect($html)
        ->toContain('aria-invalid="true"')
        ->toContain('Valor alto demais')
        ->toMatch('/type="hidden"[^>]*name="discount"/s');
});
