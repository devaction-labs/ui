<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;

test('components speak english by default and portuguese with pt_BR', function () {
    App::setLocale('en');

    expect(Blade::render('<x-ui::combobox><x-ui::combobox.option value="a">A</x-ui::combobox.option></x-ui::combobox>'))
        ->toContain('Select…')
        ->toContain('No results.');

    expect(Blade::render('<x-ui::cookie-consent :fixed="false" force />'))
        ->toContain('Accept all')
        ->toContain('Necessary only');

    App::setLocale('pt_BR');

    expect(Blade::render('<x-ui::combobox><x-ui::combobox.option value="a">A</x-ui::combobox.option></x-ui::combobox>'))
        ->toContain('Selecione…')
        ->toContain('Nada encontrado.');

    expect(Blade::render('<x-ui::cookie-consent :fixed="false" force />'))
        ->toContain('Aceitar todos')
        ->toContain('Só necessários');
});

test('every translation key used by the components exists in pt_BR.json', function () {
    $translations = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/lang/pt_BR.json'), true);
    $keys = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__DIR__, 2).'/resources/views/components')) as $file) {
        if ($file->isFile()) {
            preg_match_all("/__\\('((?:[^'\\\\]|\\\\.)+)'/", (string) file_get_contents($file->getPathname()), $matches);
            array_push($keys, ...array_map(stripslashes(...), $matches[1]));
        }
    }

    $missing = array_values(array_diff(array_unique($keys), array_keys($translations)));

    expect($keys)->not->toBeEmpty()
        ->and($missing)->toBe([]);
});

test('the kit javascript translates its own messages from the html lang', function () {
    $script = (string) file_get_contents(dirname(__DIR__, 2).'/resources/js/devaction-ui.js');

    expect($script)
        ->toContain('const translate = (text, replacements = {}) =>')
        ->toContain("document.documentElement.lang || 'en'")
        ->toContain("'Last 7 days': 'Últimos 7 dias'")
        ->toContain('t: translate,');
});
