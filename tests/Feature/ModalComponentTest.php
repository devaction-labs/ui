<?php

use DevAction\Ui\Livewire\InteractsWithModals;
use Illuminate\Support\Facades\Blade;

test('modal renders a dialog wired to alpine with the design panel', function () {
    $html = Blade::render('<x-ui::modal name="publicar">Corpo</x-ui::modal>');

    expect($html)
        ->toContain('data-modal')
        ->toContain('data-modal-name="publicar"')
        ->toContain("x-data=\"uiModal({ name: 'publicar', dismissible: true, escapable: true })\"")
        ->toContain('x-modelable="open"')
        ->toContain('<dialog')
        ->toContain('wire:ignore.self')
        ->toContain('x-on:cancel="cancel($event)"')
        ->toContain('backdrop:bg-[rgb(9_9_11/0.42)]')
        ->toContain('open:animate-modal-in')
        ->toContain('w-[520px]')
        ->toContain('rounded-[18px]')
        ->toContain('aria-label="Fechar"')
        ->toContain('Corpo');
});

test('modal sizes match the design widths', function (string $size, string $width) {
    expect(Blade::render('<x-ui::modal :size="$size">x</x-ui::modal>', ['size' => $size]))->toContain($width);
})->with([
    ['sm', 'w-[400px]'],
    ['md', 'w-[520px]'],
    ['lg', 'w-[720px]'],
]);

test('modal flags control dismiss, escape and the close button', function () {
    $html = Blade::render('<x-ui::modal :dismissible="false" :escapable="false" :closable="false">x</x-ui::modal>');

    expect($html)
        ->toContain('dismissible: false, escapable: false')
        ->not->toContain('aria-label="Fechar"');
});

test('modal sends livewire and alpine listeners to the wrapper and classes to the panel', function () {
    $html = Blade::render('<x-ui::modal wire:model="showConfirm" x-on:close="saved = false" class="md:w-96">x</x-ui::modal>');

    expect($html)
        ->toMatch('/data-modal\b[^>]*wire:model="showConfirm"/s')
        ->toMatch('/data-modal\b[^>]*x-on:close="saved = false"/s')
        ->toMatch('/<div class="relative flex[^"]*md:w-96"/');
});

test('modal footer renders with divider and subtle background', function () {
    $html = Blade::render('<x-ui::modal>x<x-slot:footer><x-ui::button>Cancelar</x-ui::button></x-slot:footer></x-ui::modal>');

    expect($html)->toContain('border-t border-line-soft bg-subtle px-5 py-3.5')->toContain('Cancelar');
});

test('flyout modal opens from the chosen side', function (string $position, string $expected) {
    $html = Blade::render('<x-ui::modal flyout :position="$position">x</x-ui::modal>', ['position' => $position]);

    expect($html)->toContain('open:animate-flyout-in')->toContain($expected);
})->with([
    ['right', 'my-0 mr-0 ml-auto h-dvh'],
    ['left', 'my-0 mr-auto ml-0 h-dvh'],
    ['bottom', 'mx-0 mt-auto mb-0 w-full'],
]);

test('trigger and close dispatch through alpine', function () {
    $trigger = Blade::render('<x-ui::modal.trigger name="busca" shortcut="cmd.k"><button>Abrir</button></x-ui::modal.trigger>');
    $close = Blade::render('<x-ui::modal.close><button>Fechar</button></x-ui::modal.close>');

    expect($trigger)
        ->toContain("x-on:click=\"\$modal('busca').show()\"")
        ->toContain("x-on:keydown.cmd.k.window.prevent=\"\$modal('busca').show()\"");
    expect($close)->toContain("x-on:click=\"\$dispatch('ui-modal-close-self')\"");
});

test('livewire trait dispatches the modal events', function () {
    $component = new class
    {
        use InteractsWithModals;

        /** @var list<array<int|string, mixed>> */
        public array $dispatched = [];

        public function dispatch(string $event, mixed ...$params): void
        {
            $this->dispatched[] = ['event' => $event, ...$params];
        }
    };

    $component->modal('confirmar')->show();
    $component->modal('confirmar')->close();
    $component->closeModals();

    expect($component->dispatched)->toBe([
        ['event' => 'ui-modal', 'name' => 'confirmar', 'action' => 'show'],
        ['event' => 'ui-modal', 'name' => 'confirmar', 'action' => 'close'],
        ['event' => 'ui-modal', 'action' => 'close-all'],
    ]);
});
