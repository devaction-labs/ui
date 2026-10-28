<?php

use DevAction\Ui\Support\ComponentManifest;

test('find resolves a child component through its owner reference (U7)', function () {
    $component = (new ComponentManifest)->find('table.row');

    expect($component)->not->toBeNull()
        ->and($component['group'])->toBe('Data')
        ->and($component['kind'])->toBe('component')
        ->and($component['tag'])->toBe('x-ui::table.row')
        ->and($component['reference'])->toBe('skill/references/data-table.md')
        ->and($component['example'])->toBe((new ComponentManifest)->find('table')['example'])
        ->and($component['summary'])->not->toBe('');
});

test('find also accepts the reference name and returns null for unknown names', function () {
    $manifest = new ComponentManifest;

    expect($manifest->find('nao-existe'))->toBeNull()
        ->and($manifest->find('button'))->not->toBeNull();
});

test('build is deterministic (U8)', function () {
    $manifest = new ComponentManifest;

    expect(json_encode($manifest->build()))->toBe(json_encode((new ComponentManifest)->build()));
});

test('build sorts components by name and keys alphabetically (U9)', function () {
    $components = (new ComponentManifest)->build()['components'];
    $names = array_column($components, 'name');
    $sorted = $names;
    sort($sorted, SORT_STRING);

    $keys = array_keys($components[0]);
    $sortedKeys = $keys;
    sort($sortedKeys, SORT_STRING);

    expect($names)->toBe($sorted)
        ->and($keys)->toBe($sortedKeys)
        ->and(array_keys(['version' => 1] + (new ComponentManifest)->build()))->toBe(['version', 'components']);
});

test('build exposes the button props and attributes', function () {
    $button = (new ComponentManifest)->find('button');
    $props = array_column($button['props'], 'default', 'name');

    expect($props['variant'])->toBe('outline')
        ->and($button['attributes'])->toContain('icon:trailing');
});

test('search filters by group and by accent-insensitive query', function () {
    $manifest = new ComponentManifest;

    $data = $manifest->search('Data', null);
    $query = $manifest->search(null, 'DATA TABLE');

    expect($data)->not->toBeEmpty()
        ->and(array_unique(array_column($data, 'group')))->toBe(['Data'])
        ->and(array_column($query, 'name'))->toContain('table');
});

test('examplePath returns an existing file or null', function () {
    $manifest = new ComponentManifest;
    $path = $manifest->examplePath('table');

    expect($manifest->examplePath('nao-existe'))->toBeNull()
        ->and($path === null || is_file($path))->toBeTrue();
});
