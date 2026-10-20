<?php

use Illuminate\Support\Facades\Blade;

test('drawer renders a floating dialog with header, body and footer', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::drawer name="perfil" heading="Editar perfil" description="Altere seus dados.">
            Corpo
            <x-slot:footer><x-ui::button>Salvar</x-ui::button></x-slot:footer>
        </x-ui::drawer>
        BLADE);

    expect($html)
        ->toContain('data-drawer')
        ->toContain("x-data=\"uiModal({ name: 'perfil'")
        ->toContain('<dialog')
        ->toContain('my-3 mr-3 ml-auto h-[calc(100dvh-1.5rem)]')
        ->toContain('open:animate-flyout-in')
        ->toContain('rounded-2xl border border-line')
        ->toContain('w-[480px]')
        ->toContain('Editar perfil')
        ->toContain('Altere seus dados.')
        ->toContain('aria-label="Fechar"')
        ->toContain('Corpo')
        ->toContain('border-t border-line-soft px-7');
});

test('drawer variants and sides position the panel', function (string $attributes, string $expected) {
    expect(Blade::render("<x-ui::drawer {$attributes}>x</x-ui::drawer>"))->toContain($expected);
})->with([
    ['variant="docked"', 'my-0 mr-0 ml-auto h-dvh'],
    ['side="left"', 'my-3 mr-auto ml-3'],
    ['side="left" variant="docked"', 'border-r border-line'],
    ['side="bottom"', 'rounded-t-[22px]'],
    ['side="bottom"', 'open:animate-sheet-in'],
    ['size="sm"', 'w-[384px]'],
    ['size="lg"', 'w-[640px]'],
]);

test('bottom sheet shows the handle and closable can hide the close button', function () {
    expect(Blade::render('<x-ui::drawer side="bottom">x</x-ui::drawer>'))->toContain('h-1 w-10');
    expect(Blade::render('<x-ui::drawer :closable="false">x</x-ui::drawer>'))->not->toContain('aria-label="Fechar"');
});

test('drawer sends livewire listeners to the wrapper', function () {
    expect(Blade::render('<x-ui::drawer wire:model="editing">x</x-ui::drawer>'))
        ->toMatch('/data-drawer\b[^>]*wire:model="editing"/s');
});
