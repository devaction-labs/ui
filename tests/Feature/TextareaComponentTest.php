<?php

use Illuminate\Support\Facades\Blade;

test('textarea renders the bordered control with rows and content', function () {
    $html = Blade::render('<x-ui::textarea rows="5" wire:model="scope">Texto inicial</x-ui::textarea>');

    expect($html)
        ->toContain('data-textarea')
        ->toContain('rounded-[14px] border')
        ->toContain('rows="5"')
        ->toContain('resize-y')
        ->toContain('wire:model="scope"')
        ->toContain('>Texto inicial</textarea>')
        ->not->toContain('data-field');
});

test('textarea with maxlength shows a live counter in the footer', function () {
    $html = Blade::render('<x-ui::textarea maxlength="500" />');

    expect($html)
        ->toContain('x-data="{ count: 0, max: 500 }"')
        ->toContain('x-on:input="count = $el.value.length"')
        ->toContain('border-t border-line-soft bg-subtle')
        ->toContain('/500</span>');
});

test('textarea auto rows grow with content', function () {
    expect(Blade::render('<x-ui::textarea rows="auto" />'))
        ->toContain('field-sizing-content')
        ->not->toMatch('/\srows="/');
});

test('textarea field, error and resize options', function () {
    $field = Blade::render('<x-ui::textarea label="Escopo" description="Ajuda" wire:model="scope" />');
    $error = Blade::render('<x-ui::textarea error="Muito curto" resize="none" />');

    expect($field)->toContain('data-field')->toContain('for="ui-textarea-scope"')->toContain('Ajuda');
    expect($error)->toContain('aria-invalid="true"')->toContain('Muito curto')->toContain('resize-none');
});

test('textarea footer slot renders actions', function () {
    expect(Blade::render('<x-ui::textarea><x-slot:footer><button>Anexar</button></x-slot:footer></x-ui::textarea>'))
        ->toContain('<button>Anexar</button>');
});
