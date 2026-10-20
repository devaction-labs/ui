<?php

use Illuminate\Support\Facades\Blade;

test('tooltip wraps the trigger with alpine behavior and an accessible bubble', function () {
    $html = Blade::render('<x-ui::tooltip content="Arquivar" kbd="E"><button>Arquivar</button></x-ui::tooltip>');

    expect($html)
        ->toContain('data-tooltip')
        ->toContain('x-data="uiTooltip({ delay: 400, toggleable: false, disabled: false })"')
        ->toContain('x-on:mouseenter="schedule()"')
        ->toContain('x-on:focusin="show()"')
        ->toContain('x-on:keydown.escape.window="hide()"')
        ->toContain('role="tooltip"')
        ->toContain('x-show="open"')
        ->toContain('bg-ink text-xs text-on-ink')
        ->toContain('bottom-full mb-(--tooltip-gap) left-1/2 -translate-x-1/2')
        ->toContain('--tooltip-gap: 6px')
        ->toContain('<kbd class="rounded-[4px] bg-current/15')
        ->toContain('>E</kbd>');
});

test('tooltip supports every side and alignment', function (string $attributes, string $expected) {
    expect(Blade::render("<x-ui::tooltip content=\"x\" {$attributes}><button>x</button></x-ui::tooltip>"))->toContain($expected);
})->with([
    ['position="bottom"', 'top-full mt-(--tooltip-gap)'],
    ['position="left"', 'right-full mr-(--tooltip-gap) top-1/2 -translate-y-1/2'],
    ['position="right"', 'left-full ml-(--tooltip-gap)'],
    ['align="start"', 'left-0'],
    ['align="end"', 'right-0'],
    ['gap="10"', '--tooltip-gap: 10px'],
]);

test('rich tooltip renders the content slot', function () {
    $html = Blade::render('<x-ui::tooltip><button>?</button><x-slot:content><strong>RingScore 69</strong> 18 de 26 itens.</x-slot:content></x-ui::tooltip>');

    expect($html)
        ->toContain('max-w-[280px] flex-col')
        ->toContain('<strong>RingScore 69</strong>');
});

test('toggleable tooltip opens on click', function () {
    expect(Blade::render('<x-ui::tooltip content="x" toggleable><button>x</button></x-ui::tooltip>'))
        ->toContain('toggleable: true')
        ->toContain('x-on:click="toggle()"');
});

test('button tooltip shorthand wraps the button and labels icon only buttons', function () {
    $html = Blade::render('<x-ui::button icon="archive-02" tooltip="Arquivar" tooltip:kbd="E" tooltip:position="bottom" />');

    expect($html)
        ->toContain('data-tooltip')
        ->toContain('top-full')
        ->toContain('>E</kbd>')
        ->toMatch('/<button[^>]*aria-label="Arquivar"/s')
        ->not->toContain('tooltip:kbd')
        ->not->toContain('tooltip:position');
});

test('button tooltip keeps an explicit aria label', function () {
    expect(Blade::render('<x-ui::button icon="delete-02" tooltip="Excluir" aria-label="Excluir o PR" />'))
        ->toContain('aria-label="Excluir o PR"')
        ->not->toContain('aria-label="Excluir"');
});

test('button without tooltip renders no wrapper', function () {
    expect(Blade::render('<x-ui::button>Salvar</x-ui::button>'))->not->toContain('data-tooltip');
});
