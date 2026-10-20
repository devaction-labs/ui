<?php

use Illuminate\Support\Facades\Blade;

test('checkbox renders a native styled input with label and description', function () {
    $html = Blade::render('<x-ui::checkbox wire:model="auto" label="Review automático" description="A IA revisa." checked />');

    expect($html)
        ->toContain('<label')
        ->toContain('type="checkbox"')
        ->toContain('wire:model="auto"')
        ->toMatch('/\schecked[\s>="]/')
        ->toContain('appearance-none rounded-md border-[1.5px]')
        ->toContain('checked:bg-ink')
        ->toContain('data-icon="tick-02"')
        ->toContain('Review automático')
        ->toContain('A IA revisa.');
});

test('checkbox indeterminate state is set through alpine', function () {
    expect(Blade::render('<x-ui::checkbox label="Todos" indeterminate />'))
        ->toContain('x-init="$el.indeterminate = true"')
        ->toContain('data-icon="minus-sign"');
});

test('checkbox group syncs values with alpine and supports variants', function () {
    $html = Blade::render('<x-ui::checkbox.group wire:model="channels" label="Avisar por" variant="pills"><x-ui::checkbox value="email" label="E-mail" /></x-ui::checkbox.group>');

    expect($html)
        ->toContain('<fieldset')
        ->toContain('data-choice-group')
        ->toContain('x-data="uiChoiceGroup({ multiple: true })"')
        ->toContain('x-modelable="value"')
        ->toContain('wire:model="channels"')
        ->toContain('<legend')
        ->toContain('rounded-full')
        ->toContain('sr-only');
});

test('radio group shares the name and renders cards', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::radio.group name="modelo" variant="cards" wire:model="model">
            <x-ui::radio value="fixo" icon="file-edit" label="Escopo fechado" />
            <x-ui::radio value="horas" label="Banco de horas" />
        </x-ui::radio.group>
        BLADE);

    expect($html)
        ->toContain('role="radiogroup"')
        ->toContain('x-data="uiChoiceGroup({ multiple: false })"')
        ->toContain('wire:model="model"')
        ->toContain('type="radio"')
        ->toContain('name="modelo"')
        ->toContain('has-checked:border-ink has-checked:bg-wrap')
        ->toContain('data-icon="file-edit"')
        ->toContain('checked:border-[6px]');
    expect(substr_count($html, 'name="modelo"'))->toBe(2);
});

test('disabled choices look disabled', function () {
    expect(Blade::render('<x-ui::checkbox label="x" disabled />'))->toContain('cursor-not-allowed text-muted');
    expect(Blade::render('<x-ui::radio.group><x-ui::radio label="x" disabled /></x-ui::radio.group>'))->toContain('cursor-not-allowed');
});
