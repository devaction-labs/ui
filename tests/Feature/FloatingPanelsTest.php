<?php

use Illuminate\Support\Facades\Blade;

test('floating panels escape clipping containers through x-ui-float', function (string $blade, string $expected) {
    expect(Blade::render($blade))->toContain($expected);
})->with([
    'date picker' => ['<x-ui::date-picker label="Vencimento" value="2026-10-23" />', 'x-ui-float="{ reference: $refs.trigger }"'],
    'combobox' => ['<x-ui::combobox label="Empresa"><x-ui::combobox.option value="1">Acme</x-ui::combobox.option></x-ui::combobox>', 'x-ui-float="{ match: true }"'],
    'autocomplete' => ['<x-ui::autocomplete label="Cidade"><x-ui::autocomplete.item>São Paulo</x-ui::autocomplete.item></x-ui::autocomplete>', 'x-ui-float="{ match: true }"'],
    'pillbox' => ['<x-ui::pillbox label="Tags" :suggestions="[\'VIP\']" />', 'x-ui-float="{ match: true }"'],
    'color picker' => ['<x-ui::color-picker label="Cor" value="#2563EB" />', 'x-ui-float'],
    'filter menu' => ['<x-ui::filter-menu align="end" />', 'x-ui-float="{ align: \'end\' }"'],
    'tooltip' => ['<x-ui::tooltip content="Arquivar" position="bottom"><button>x</button></x-ui::tooltip>', 'x-ui-float="{ placement: \'bottom\', align: \'center\', gap: 6 }"'],
    'menu' => ['<x-ui::dropdown position="top" align="end" gap="10" offset="4"><button>x</button><x-ui::menu><x-ui::menu.item>A</x-ui::menu.item></x-ui::menu></x-ui::dropdown>', 'x-ui-float="{ placement: \'top\', align: \'end\', gap: 10, offset: 4 }"'],
    'popover' => ['<x-ui::dropdown><button>x</button><x-ui::popover>Oi</x-ui::popover></x-ui::dropdown>', 'x-ui-float="{ placement: \'bottom\', align: \'start\', gap: 6, offset: 0 }"'],
]);

test('kit javascript registers the x-ui-float directive with fixed positioning', function () {
    $script = file_get_contents(dirname(__DIR__, 2).'/resources/js/devaction-ui.js');

    expect($script)
        ->toContain("Alpine.directive('ui-float'")
        ->toContain("position: 'fixed'")
        ->toContain('fixedContainingBlock');
});
