<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

test('input renders the bordered control with icon and attributes on the input', function () {
    $html = Blade::render('<x-ui::input icon="user" placeholder="Nome" wire:model="name" />');

    expect($html)
        ->toContain('data-input')
        ->toContain('h-11')
        ->toContain('border-line-strong bg-card focus-within:border-ink')
        ->toContain('data-icon="user"')
        ->toContain('type="text"')
        ->toContain('wire:model="name"')
        ->toContain('placeholder="Nome"')
        ->not->toContain('data-field');
});

test('input with label builds a full field with linked ids', function () {
    $html = Blade::render('<x-ui::input label="Nome" description="Como aparece." wire:model="contact.name" />');

    expect($html)
        ->toContain('data-field')
        ->toContain('for="ui-input-contactname"')
        ->toContain('id="ui-input-contactname"')
        ->toContain('aria-describedby="ui-input-contactname-hint"')
        ->toContain('Como aparece.');
});

test('input shows the validation error for its wire model', function () {
    View::share('errors', (new ViewErrorBag)->put('default', new MessageBag(['email' => ['Falta o domínio.']])));

    $html = Blade::render('<x-ui::input label="E-mail" wire:model="email" />');

    expect($html)
        ->toContain('aria-invalid="true"')
        ->toContain('border-missing ring-4')
        ->toContain('role="alert"')
        ->toContain('Falta o domínio.');

    View::share('errors', new ViewErrorBag);
});

test('input sizes and states', function (string $attributes, string $expected) {
    expect(Blade::render("<x-ui::input {$attributes} />"))->toContain($expected);
})->with([
    ['size="sm"', 'h-9'],
    ['size="lg"', 'h-13'],
    ['disabled', 'border-dashed border-line-strong bg-subtle'],
    ['readonly value="x"', 'font-mono text-[13px]'],
    ['invalid', 'aria-invalid="true"'],
    ['prefix="R$"', '>R$</span>'],
    ['kbd="⌘K"', '⌘K</kbd>'],
]);

test('input helpers render accessible buttons', function () {
    expect(Blade::render('<x-ui::input clearable />'))->toContain('aria-label="Limpar"');
    expect(Blade::render('<x-ui::input copyable value="x" readonly />'))->toContain('aria-label="Copiar"');
    expect(Blade::render('<x-ui::input type="password" viewable />'))
        ->toContain("x-bind:type=\"revealed ? 'text' : 'password'\"")
        ->toContain('aria-label="Mostrar senha"');
});

test('select renders a native control with placeholder and field', function () {
    $html = Blade::render('<x-ui::select label="Etapa" placeholder="Escolha" wire:model="stage"><x-ui::select.option value="lead">Lead</x-ui::select.option></x-ui::select>');

    expect($html)
        ->toContain('data-field')
        ->toContain('for="ui-select-stage"')
        ->toContain('<select')
        ->toContain('appearance-none')
        ->toContain('wire:model="stage"')
        ->toContain('<option value="" disabled')
        ->toContain('<option value="lead"')
        ->toContain('data-icon="arrow-down-01"');
});

test('select class sizes the wrapper so it sits inline in toolbars', function () {
    $html = Blade::render('<x-ui::select size="sm" aria-label="Responsável" class="w-44"><x-ui::select.option value="">Todos</x-ui::select.option></x-ui::select>');

    expect($html)
        ->toContain('data-select class="relative flex min-w-0 w-44"')
        ->not->toContain('w-full min-w-0 w-44');

    expect(Blade::render('<x-ui::select label="Etapa" class="max-w-xs"><x-ui::select.option value="a">A</x-ui::select.option></x-ui::select>'))
        ->toMatch('/data-field\s+class="[^"]*max-w-xs/');
});

test('input group joins controls and addons', function () {
    $html = Blade::render('<x-ui::input.group><x-ui::input.group.prefix>https://</x-ui::input.group.prefix><x-ui::input /></x-ui::input.group>');

    expect($html)
        ->toContain('data-input-group')
        ->toContain('*:not-first:rounded-l-none')
        ->toContain('https://');
});

test('label, description and error render standalone', function () {
    expect(Blade::render('<x-ui::label badge="Opcional">Telefone</x-ui::label>'))->toContain('Opcional');
    expect(Blade::render('<x-ui::description>Ajuda</x-ui::description>'))->toContain('text-[13px]');
    expect(Blade::render('<x-ui::error message="Obrigatório" />'))->toContain('Obrigatório')->toContain('role="alert"');
    expect(Blade::render('<x-ui::error name="nada" />'))->not->toContain('role="alert"');
});

test('input class sizes the wrapper and the clear button hides while empty', function () {
    $html = Blade::render('<x-ui::input icon="search-01" placeholder="Buscar" size="sm" clearable class="max-w-xs" />');

    expect($html)
        ->toMatch('/data-input[^>]*class="[^"]*max-w-xs/')
        ->not->toMatch('/<input[^>]*max-w-xs/')
        ->toContain('has-[input:placeholder-shown]:[&_[data-input-clear]]:hidden')
        ->toContain('data-input-clear');

    expect(Blade::render('<x-ui::input clearable />'))->toContain("placeholder=\"\u{00A0}\"");

    expect(Blade::render('<x-ui::input label="Nome" class="sm:col-span-2" />'))
        ->toMatch('/data-field\s+class="[^"]*sm:col-span-2/');
});
