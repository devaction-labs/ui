<?php

use Illuminate\Support\Facades\Blade;

test('otp input renders one box per digit with one-time-code autofill', function () {
    $html = Blade::render('<x-ui::otp-input wire:model="code" submit="verify" resend="sendCode" cooldown="30" />');

    expect($html)
        ->toContain('data-otp-input')
        ->toContain("submit: 'verify'")
        ->toContain("resend: 'sendCode'")
        ->toContain('cooldown: 30')
        ->toContain('wire:model="code"')
        ->toContain('autocomplete="one-time-code"')
        ->toContain('inputmode="numeric"')
        ->toContain('aria-label="Dígito 6 de 6"')
        ->toContain('h-0.5 w-2.5')
        ->toContain('Reenviar código')
        ->and(substr_count($html, 'aria-label="Dígito'))->toBe(6)
        ->and(substr_count($html, 'one-time-code'))->toBe(1);
});

test('otp input shows error and success states', function () {
    expect(Blade::render('<x-ui::otp-input length="4" error="Código incorreto." />'))
        ->toContain('role="alert"')
        ->toContain('Código incorreto.')
        ->toContain('border-missing')
        ->toContain('aria-invalid="true"')
        ->not->toContain('h-0.5 w-2.5');

    expect(Blade::render('<x-ui::otp-input success="Verificado." mode="alphanumeric" />'))
        ->toContain('border-ok-fg')
        ->toContain('Verificado.')
        ->toContain('inputmode="text"')
        ->toContain('numeric: false');
});
