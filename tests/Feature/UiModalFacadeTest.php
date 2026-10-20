<?php

use DevAction\Ui\Ui;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

test('Ui::modal controls modals from inside a livewire request', function () {
    $component = new class extends Component
    {
        public function delete(): void
        {
            Ui::modal('confirm')->show();
        }

        public function done(): void
        {
            Ui::modal('confirm')->close();
            Ui::modals()->close();
        }

        public function render(): string
        {
            return '<div></div>';
        }
    };

    Livewire::test($component::class)
        ->call('delete')
        ->assertDispatched('ui-modal', name: 'confirm', action: 'show')
        ->call('done')
        ->assertDispatched('ui-modal', name: 'confirm', action: 'close')
        ->assertDispatched('ui-modal', action: 'close-all');
});

test('Ui::modal outside livewire explains how to open modals in javascript', function () {
    Ui::modal('confirm')->show();
})->throws(RuntimeException::class, 'só funciona durante uma requisição do Livewire');

test('heading, text and spacer follow the flux primitives', function () {
    expect(Blade::render('<x-ui::heading size="lg">Atualizar perfil</x-ui::heading>'))
        ->toContain('<div data-heading class="font-semibold text-ink text-lg tracking-[-0.01em]">Atualizar perfil</div>');

    expect(Blade::render('<x-ui::heading level="2">Contratos</x-ui::heading>'))->toContain('<h2 data-heading');

    expect(Blade::render('<x-ui::text class="mt-2">Mude seus dados.</x-ui::text>'))
        ->toContain('<p data-text class="leading-normal text-sm text-ink-3 mt-2">Mude seus dados.</p>');

    expect(Blade::render('<x-ui::text variant="strong" size="sm">Total</x-ui::text>'))->toContain('font-medium text-ink');

    expect(Blade::render('<x-ui::spacer />'))->toContain('data-spacer aria-hidden="true" class="flex-1"');
});
