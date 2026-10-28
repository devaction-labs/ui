<?php

use DevAction\Ui\Support\BladeComponentParser;
use DevAction\Ui\Support\ComponentManifest;
use DevAction\Ui\Support\SkillMirror;
use Illuminate\Support\Facades\File;

$GLOBALS['supportEdgeRoots'] = [];

function supportEdgeRoot(): string
{
    $root = sys_get_temp_dir().'/ui-support-edge-'.uniqid();
    File::ensureDirectoryExists($root);
    $GLOBALS['supportEdgeRoots'][] = $root;

    return $root;
}

beforeEach(fn () => ComponentManifest::flush());

afterEach(function () {
    foreach ($GLOBALS['supportEdgeRoots'] as $root) {
        File::deleteDirectory($root);
    }

    $GLOBALS['supportEdgeRoots'] = [];

    ComponentManifest::flush();
});

test('parser handles escaped quote in props and unbalanced @props', function () {
    $root = supportEdgeRoot();
    File::put($root.'/a.blade.php', "@props(['x' => 'it\\'s (ok)'])\n");
    File::put($root.'/b.blade.php', "@props(['x' => 1\n");

    $parser = new BladeComponentParser;
    $a = $parser->parse($root.'/a.blade.php', $root);
    $b = $parser->parse($root.'/b.blade.php', $root);

    expect($a['props'][0]['default'])->toBe("it's (ok)")
        ->and($b['props'])->toBe([]);
});

test('manifest build skips orphans/icons, tolerates bad references and missing dirs', function () {
    $root = supportEdgeRoot();
    File::ensureDirectoryExists($root.'/resources/views/components/icons');
    File::ensureDirectoryExists($root.'/skill/references');
    File::put($root.'/resources/views/components/orphan.blade.php', "x\n");
    File::put($root.'/resources/views/components/icons/i.blade.php', "x\n");
    File::put($root.'/resources/views/components/card.blade.php', "x\n");
    File::put($root.'/skill/references/none.md', "# sem front matter\n");
    File::put($root.'/skill/references/bad.md', "---\nlinha invalida\n---\n# Bad\n");
    File::put($root.'/skill/references/card-ref.md', "---\ngroup: G\ncomponents: [card]\n---\nsem titulo\n");

    $manifest = new ComponentManifest($root);
    $names = array_column($manifest->build()['components'], 'name');
    $card = $manifest->build()['components'][0];

    expect($names)->toBe(['card'])
        ->and($card['summary'])->toBe('')
        ->and($card['example'])->toBeNull()
        ->and($manifest->examplePath('card'))->toBeNull()
        ->and($manifest->examplePath('nada'))->toBeNull()
        ->and((new ComponentManifest($root.'/vazio'))->build()['components'])->toBe([]);
});

test('frontMatter edge cases', function () {
    expect(ComponentManifest::frontMatter("sem\n"))->toBeNull()
        ->and(ComponentManifest::frontMatter("---\n\nkind: x\n---\n"))->toBe(['kind' => 'x'])
        ->and(ComponentManifest::frontMatter("---\nkind\n---\n"))->toBeNull()
        ->and(ComponentManifest::frontMatter("---\ncomponents: [a, b\n---\n"))->toBeNull()
        ->and(ComponentManifest::frontMatter("---\ncomponents: []\n---\n"))->toBe(['components' => []]);
});

test('manifest load memoizes, flushes, and tolerates missing or invalid json; find by reference; search combos', function () {
    $root = supportEdgeRoot();
    $manifest = new ComponentManifest($root);

    expect($manifest->load()->search())->toBe([]);

    File::ensureDirectoryExists($root.'/resources');
    $data = ['components' => [
        ['name' => 'alert', 'summary' => 'Aviso', 'group' => 'Feedback', 'reference' => 'skill/references/callout-box.md', 'example' => null],
        ['name' => 'tabela', 'summary' => 'Ação rápida', 'group' => 'Data', 'reference' => 'skill/references/tabela.md', 'example' => null],
    ]];
    File::put($root.'/'.ComponentManifest::FILE, json_encode($data, JSON_THROW_ON_ERROR));

    expect($manifest->load()->search())->toHaveCount(2);
    ComponentManifest::flush();

    expect($manifest->find('callout-box')['name'])->toBe('alert')
        ->and($manifest->find(' ALERT ')['name'])->toBe('alert')
        ->and($manifest->search('Data'))->toHaveCount(1)
        ->and($manifest->search('', 'acao'))->toHaveCount(1)
        ->and($manifest->search('Data', 'aviso'))->toBe([])
        ->and($manifest->search('Feedback', 'ALERT'))->toHaveCount(1)
        ->and($manifest->search(null, null))->toHaveCount(2);

    File::put($root.'/'.ComponentManifest::FILE, '{"components": "x"}');
    ComponentManifest::flush();

    expect($manifest->load()->search())->toBe([]);
});

test('mirror diff reports missing, different and extra; sync repairs and prunes', function () {
    $root = supportEdgeRoot();
    $mirror = $root.'/'.SkillMirror::MIRROR;
    File::ensureDirectoryExists($root.'/skill/sub');
    File::ensureDirectoryExists($mirror.'/vazia/aninhada');
    File::put($root.'/skill/a.md', 'a');
    File::put($root.'/skill/sub/b.md', 'b');
    File::put($mirror.'/a.md', 'outro');
    File::put($mirror.'/extra.md', 'x');

    $sut = new SkillMirror($root);

    expect($sut->diff())->toBe([
        SkillMirror::MIRROR.'/a.md (diferente)',
        SkillMirror::MIRROR.'/extra.md (sobrando)',
        SkillMirror::MIRROR.'/sub/b.md (faltando)',
    ]);

    $sut->sync();

    expect($sut->diff())->toBe([])
        ->and(is_dir($mirror.'/vazia'))->toBeFalse()
        ->and(file_get_contents($mirror.'/sub/b.md'))->toBe('b');
});

test('mirror sync with empty source and no target dir does nothing', function () {
    $root = supportEdgeRoot();

    (new SkillMirror($root))->sync();

    expect(is_dir($root.'/'.SkillMirror::MIRROR))->toBeFalse();
});

test('mirror sync never follows symlinks out of the mirror', function () {
    $root = supportEdgeRoot();
    $mirror = $root.'/'.SkillMirror::MIRROR;
    File::ensureDirectoryExists($root.'/skill');
    File::ensureDirectoryExists($mirror);
    File::ensureDirectoryExists($root.'/outside/empty/deep');
    File::put($root.'/skill/a.md', 'a');
    symlink($root.'/outside', $mirror.'/link');

    (new SkillMirror($root))->sync();

    expect(is_dir($root.'/outside/empty/deep'))->toBeTrue();
});

test('mirror ignores symlinks in the source skill', function () {
    $root = supportEdgeRoot();
    File::ensureDirectoryExists($root.'/skill');
    File::ensureDirectoryExists($root.'/secret');
    File::put($root.'/secret/s.md', 'segredo');
    File::put($root.'/skill/a.md', 'a');
    symlink($root.'/secret/s.md', $root.'/skill/leak.md');
    symlink($root.'/secret', $root.'/skill/dir');

    (new SkillMirror($root))->sync();

    $mirror = $root.'/'.SkillMirror::MIRROR;
    expect(file_exists($mirror.'/a.md'))->toBeTrue()
        ->and(file_exists($mirror.'/leak.md'))->toBeFalse()
        ->and(file_exists($mirror.'/dir/s.md'))->toBeFalse();
});

test('mirror sync throws when copy fails', function () {
    $root = supportEdgeRoot();
    $mirror = $root.'/'.SkillMirror::MIRROR;
    File::ensureDirectoryExists($root.'/skill');
    File::ensureDirectoryExists($mirror.'/a.md');
    File::put($mirror.'/a.md/keep', 'x');
    File::put($root.'/skill/a.md', 'a');

    expect(fn () => (new SkillMirror($root))->sync())->toThrow(RuntimeException::class);
});

test('manifest load reloads when the json file changes', function () {
    $root = supportEdgeRoot();
    File::ensureDirectoryExists($root.'/resources');
    $path = $root.'/'.ComponentManifest::FILE;
    File::put($path, (string) json_encode(['components' => [['name' => 'a', 'reference' => 'r', 'summary' => '', 'group' => '']]]));
    touch($path, time() - 100);
    $manifest = new ComponentManifest($root);

    expect($manifest->load()->find('a'))->not->toBeNull();

    File::put($path, (string) json_encode(['components' => [['name' => 'b', 'reference' => 'r', 'summary' => '', 'group' => '']]]));
    touch($path, time() + 100);

    expect($manifest->load()->find('b'))->not->toBeNull()
        ->and($manifest->find('a'))->toBeNull();
});
