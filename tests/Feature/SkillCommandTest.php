<?php

use DevAction\Ui\Support\ComponentManifest;
use DevAction\Ui\Support\SkillMirror;
use DevAction\Ui\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Testing\PendingCommand;

const SKILL_MIRROR_DIR = 'resources/boost/skills/devaction-ui-development';

/**
 * Monta um pacote mínimo em pasta temporária e aponta o manifesto e o espelho para ele.
 */
/**
 * @param  array<string, mixed>  $parameters
 */
function skillCommand(TestCase $case, array $parameters = []): PendingCommand
{
    $command = $case->artisan('ui:skill', $parameters);
    assert($command instanceof PendingCommand);

    return $command;
}

function skillTempRoot(): string
{
    $root = storage_path('framework/testing/skill-root');
    File::deleteDirectory($root);
    File::ensureDirectoryExists($root.'/resources/views/components');
    File::ensureDirectoryExists($root.'/skill/references');
    File::put($root.'/resources/views/components/badge.blade.php', "{{-- Badge. --}}\n@props(['tone' => 'base'])\n<span>{{ \$slot }}</span>\n");
    File::put($root.'/skill/SKILL.md', "# Skill\n");
    File::put($root.'/skill/references/badge.md', "---\nkind: component\ngroup: Base\ncomponents: [badge]\n---\n# Badge\n\nSmall label.\n");

    app()->bind(ComponentManifest::class, fn () => new ComponentManifest($root));
    app()->bind(SkillMirror::class, fn () => new SkillMirror($root));

    return $root;
}

afterEach(fn () => File::deleteDirectory(storage_path('framework/testing/skill-root')));

test('ui:skill --check passes on the committed repo without writing (V4)', function () {
    $root = dirname(__DIR__, 2);
    $files = [$root.'/resources/ui-manifest.json', $root.'/'.SKILL_MIRROR_DIR.'/SKILL.md'];
    $before = array_map(fn (string $file) => filemtime($file), $files);
    clearstatcache();

    skillCommand($this, ['--check' => true])->assertSuccessful();

    clearstatcache();
    expect(array_map(fn (string $file) => filemtime($file), $files))->toBe($before);
});

test('ui:skill writes the manifest and the mirror, then --check passes', function () {
    $root = skillTempRoot();

    skillCommand($this, ['--check' => true])->assertFailed();

    skillCommand($this)->assertSuccessful();

    expect(File::exists($root.'/resources/ui-manifest.json'))->toBeTrue()
        ->and(File::get($root.'/'.SKILL_MIRROR_DIR.'/references/badge.md'))->toContain('# Badge')
        ->and(json_decode(File::get($root.'/resources/ui-manifest.json'), true)['components'][0]['name'])->toBe('badge');

    skillCommand($this, ['--check' => true])->assertSuccessful();
});

test('ui:skill --check fails and lists the manifest when it drifts (V5)', function () {
    $root = skillTempRoot();
    skillCommand($this)->assertSuccessful();

    File::put($root.'/resources/ui-manifest.json', "{}\n");

    skillCommand($this, ['--check' => true])
        ->expectsOutputToContain('resources/ui-manifest.json')
        ->assertFailed();

    expect(File::get($root.'/resources/ui-manifest.json'))->toBe("{}\n");
});

test('ui:skill --check fails and lists an extra file in the mirror (V6)', function () {
    $root = skillTempRoot();
    skillCommand($this)->assertSuccessful();

    File::put($root.'/'.SKILL_MIRROR_DIR.'/extra.md', 'x');

    skillCommand($this, ['--check' => true])
        ->expectsOutputToContain(SKILL_MIRROR_DIR.'/extra.md')
        ->assertFailed();

    expect(File::exists($root.'/'.SKILL_MIRROR_DIR.'/extra.md'))->toBeTrue();
});

test('ui:skill sync removes leftovers only inside the mirror', function () {
    $root = skillTempRoot();
    skillCommand($this)->assertSuccessful();

    File::put($root.'/'.SKILL_MIRROR_DIR.'/extra.md', 'x');
    File::put($root.'/resources/keep.txt', 'k');
    File::put($root.'/'.SKILL_MIRROR_DIR.'/references/badge.md', 'changed');

    skillCommand($this)->assertSuccessful();

    expect(File::exists($root.'/'.SKILL_MIRROR_DIR.'/extra.md'))->toBeFalse()
        ->and(File::exists($root.'/resources/keep.txt'))->toBeTrue()
        ->and(File::get($root.'/'.SKILL_MIRROR_DIR.'/references/badge.md'))->toContain('# Badge');
});
