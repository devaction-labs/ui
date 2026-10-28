<?php

use DevAction\Ui\Support\ComponentManifest;

/**
 * Cobertura da skill em `skill/`: front matter das references (V1),
 * blades reivindicados exatamente 1x (V2) e links no SKILL.md (V3).
 */

/**
 * Lê o front matter YAML plano (chaves simples + lista inline `[a, b]`).
 *
 * @return array<string, string|list<string>>|null null quando não há front matter válido
 */
function skillFrontMatter(string $path): ?array
{
    return ComponentManifest::frontMatter((string) file_get_contents($path));
}

/** @return list<string> */
function skillReferenceFiles(): array
{
    $files = glob(dirname(__DIR__, 2).'/skill/references/*.md') ?: [];
    sort($files);

    return $files;
}

/** @return list<string> */
function skillBladeComponentNames(): array
{
    $root = dirname(__DIR__, 2).'/resources/views/components';
    $names = [];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));

    foreach ($iterator as $file) {
        $relative = substr((string) $file, strlen($root) + 1);

        if (! str_ends_with($relative, '.blade.php') || str_starts_with($relative, 'icons/')) {
            continue;
        }

        $name = str_replace('/', '.', substr($relative, 0, -strlen('.blade.php')));
        $names[] = preg_replace('/\.index$/', '', $name) ?? $name;
    }

    sort($names);

    return $names;
}

it('has valid front matter in every reference (V1)', function () {
    $files = skillReferenceFiles();
    $errors = [];

    foreach ($files as $file) {
        $name = basename($file);
        $meta = skillFrontMatter($file);

        if ($meta === null) {
            $errors[] = "$name: front matter missing or malformed";

            continue;
        }

        if (! in_array($meta['kind'] ?? null, ['component', 'template', 'layout', 'guide'], true)) {
            $errors[] = "$name: invalid kind";
        }

        if (! is_string($meta['group'] ?? null) || $meta['group'] === '') {
            $errors[] = "$name: empty group";
        }

        if (! is_array($meta['components'] ?? null)) {
            $errors[] = "$name: components must be a list";
        } elseif ($meta['components'] === [] && ($meta['kind'] ?? null) !== 'guide') {
            $errors[] = "$name: empty components outside a guide";
        }
    }

    expect($files)->toHaveCount(86)
        ->and($errors)->toBe([]);
});

it('claims every component blade exactly once (V2)', function () {
    $claims = [];

    foreach (skillReferenceFiles() as $file) {
        $meta = skillFrontMatter($file);

        foreach (is_array($meta['components'] ?? null) ? $meta['components'] : [] as $component) {
            $claims[$component][] = basename($file);
        }
    }

    $blades = skillBladeComponentNames();

    $orphans = array_values(array_diff($blades, array_keys($claims)));
    $unknown = array_values(array_diff(array_keys($claims), $blades));
    $duplicates = array_keys(array_filter($claims, fn (array $files) => count($files) > 1));

    expect($blades)->toHaveCount(211)
        ->and($orphans)->toBe([], 'orphans: '.implode(', ', $orphans))
        ->and($duplicates)->toBe([], 'duplicates: '.implode(', ', $duplicates))
        ->and($unknown)->toBe([], 'claimed but missing blade: '.implode(', ', $unknown));
});

it('links every reference exactly once in SKILL.md (V3)', function () {
    $skill = (string) file_get_contents(dirname(__DIR__, 2).'/skill/SKILL.md');
    $problems = [];

    foreach (skillReferenceFiles() as $file) {
        $count = preg_match_all('/\]\(references\/'.preg_quote(basename($file), '/').'\)/', $skill);

        if ($count !== 1) {
            $problems[] = basename($file)." linked $count times";
        }
    }

    expect($problems)->toBe([]);
});
