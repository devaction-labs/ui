<?php

use Illuminate\Support\Facades\Blade;

test('dropdown wraps the trigger and popover with alpine behavior', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::dropdown>
            <x-ui::button>Opções</x-ui::button>
            <x-ui::popover>Conteúdo</x-ui::popover>
        </x-ui::dropdown>
        BLADE);

    expect($html)
        ->toContain('data-dropdown')
        ->toContain('x-data="uiDropdown({ hover: false })"')
        ->toContain('x-modelable="open"')
        ->toContain('x-on:click="clicked($event)"')
        ->toContain('x-on:click.outside="close()"')
        ->toContain('x-on:keydown.escape="if (open)')
        ->toContain('--dropdown-gap: 6px; --dropdown-offset: 0px')
        ->toContain('data-popover')
        ->toContain('data-dropdown-panel')
        ->toContain('x-show="open"')
        ->toContain('x-cloak')
        ->toContain('w-80 rounded-2xl border border-line bg-card p-4')
        ->toContain('top-full mt-(--dropdown-gap) left-(--dropdown-offset)')
        ->toContain('Conteúdo');
});

test('popover follows the dropdown position and alignment', function (string $attributes, string $expected) {
    expect(Blade::render("<x-ui::dropdown {$attributes}><button>x</button><x-ui::popover>y</x-ui::popover></x-ui::dropdown>"))
        ->toContain($expected);
})->with([
    ['position="top"', 'bottom-full mb-(--dropdown-gap)'],
    ['position="right"', 'left-full ml-(--dropdown-gap)'],
    ['position="left"', 'right-full mr-(--dropdown-gap)'],
    ['align="center"', 'left-1/2 -translate-x-1/2'],
    ['align="end"', 'right-(--dropdown-offset)'],
    ['gap="16" offset="32"', '--dropdown-gap: 16px; --dropdown-offset: 32px'],
]);

test('popover arrow points to the trigger', function (string $position, string $expected) {
    $html = Blade::render(
        '<x-ui::dropdown :position="$position"><button>x</button><x-ui::popover arrow>y</x-ui::popover></x-ui::dropdown>',
        ['position' => $position],
    );

    expect($html)->toContain('size-2.5 rotate-45')->toContain($expected);
})->with([
    ['bottom', '-top-[6px] border-t border-l'],
    ['top', '-bottom-[6px] border-r border-b'],
]);

test('hover dropdown opens on mouse enter', function () {
    expect(Blade::render('<x-ui::dropdown hover><button>x</button><x-ui::popover>y</x-ui::popover></x-ui::dropdown>'))
        ->toContain('uiDropdown({ hover: true })')
        ->toContain('x-on:mouseenter="enter()"');
});

test('dropdown forwards wire:model and popover keeps custom classes and role', function () {
    $html = Blade::render('<x-ui::dropdown wire:model="showDetails"><button>x</button><x-ui::popover role="dialog" aria-label="Detalhes" class="max-w-sm">y</x-ui::popover></x-ui::dropdown>');

    expect($html)
        ->toContain('wire:model="showDetails"')
        ->toContain('role="dialog"')
        ->toContain('aria-label="Detalhes"')
        ->toContain('max-w-sm');
});
