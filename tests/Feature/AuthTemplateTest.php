<?php

use Illuminate\Support\Facades\Blade;

test('login form posts with csrf and links', function () {
    $html = Blade::render('<x-ui::auth.layout brand="Acme CRM"><x-ui::auth.login action="/login" forgot-href="/esqueci" register-href="/cadastro" magic-href="/link" google="/auth/google" /></x-ui::auth.layout>');

    expect($html)
        ->toContain('data-auth-layout')
        ->toContain('data-variant="centered"')
        ->toContain('min-h-dvh')
        ->toContain('Acme CRM')
        ->toContain('<h1')
        ->toContain('method="POST" action="/login"')
        ->toContain('name="_token"')
        ->toContain('name="email"')
        ->toContain('autocomplete="current-password"')
        ->toContain('name="remember"')
        ->toContain('href="/esqueci"')
        ->toContain('href="/cadastro"')
        ->toContain('href="/link"')
        ->toContain('href="/auth/google"')
        ->toContain('ou com e-mail')
        ->not->toContain('wire:model');
});

test('auth forms bind livewire models when model is given', function () {
    $html = Blade::render('<x-ui::auth.login wire:submit="login" model="form" />');

    expect($html)
        ->toContain('wire:submit="login"')
        ->toContain('wire:model="form.email"')
        ->toContain('wire:model="form.password"')
        ->not->toContain('name="_token"');
});

test('register, magic link and reset password render their steps', function () {
    expect(Blade::render('<x-ui::auth.register terms-href="/termos" />'))
        ->toContain('name="password_confirmation"')
        ->toContain('name="terms"')
        ->toContain('href="/termos"');

    expect(Blade::render('<x-ui::auth.magic-link sent email="ana@acme.com" resend-action="/link" />'))
        ->toContain('Confira seu e-mail')
        ->toContain('ana@acme.com')
        ->toContain('Reenviar link');

    expect(Blade::render('<x-ui::auth.reset-password mode="reset" token="abc" email="ana@acme.com" />'))
        ->toContain('data-mode="reset"')
        ->toContain('name="token" value="abc"')
        ->toContain('Salvar nova senha');

    expect(Blade::render('<x-ui::auth.reset-password sent />'))->toContain('Link enviado');
});

test('split layout shows the aside panel', function () {
    expect(Blade::render('<x-ui::auth.layout variant="split" :fullscreen="false">x</x-ui::auth.layout>'))
        ->toContain('data-variant="split"')
        ->toContain('lg:grid-cols-2')
        ->toContain('<blockquote')
        ->not->toContain('min-h-dvh');
});
