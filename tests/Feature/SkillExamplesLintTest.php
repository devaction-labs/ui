<?php

use DevAction\Ui\Support\ComponentManifest;

/**
 * Lint dos exemplos da skill (D2/D3) e pareamento reference <-> exemplo (S4).
 * Cobre todos os grupos de componentes; templates e layouts ficam em SkillTemplateExamplesTest.
 */
const SKILL_EXAMPLE_COVERED_GROUPS = ['Base', 'Overlay', 'Form', 'Navigation', 'Data', 'AI', 'Primitives'];

const SKILL_EXAMPLE_RAW_PALETTE = '/\b(bg|text|border|ring|fill|stroke)-(red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose|slate|gray|zinc|neutral|stone)-\d{2,3}\b/';

/**
 * @return array<string, array{0: string}>
 */
function skillLintExampleFiles(): array
{
    $root = dirname(__DIR__, 2).'/skill/examples';
    $files = [];

    if (! is_dir($root)) {
        return $files;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
            $path = $file->getPathname();
            $files[substr($path, strlen($root) + 1)] = [$path];
        }
    }

    ksort($files);

    return $files;
}

/**
 * Front matter simples (kind/group) de cada reference, sem depender de parser YAML.
 *
 * @return array<string, array{kind: ?string, group: ?string}>
 */
function skillLintReferences(): array
{
    $dir = dirname(__DIR__, 2).'/skill/references';
    $refs = [];

    foreach (glob($dir.'/*.md') ?: [] as $path) {
        $content = (string) file_get_contents($path);
        $meta = ComponentManifest::frontMatter($content);
        $kind = is_string($meta['kind'] ?? null) ? $meta['kind'] : null;
        $group = is_string($meta['group'] ?? null) ? $meta['group'] : null;

        $refs[basename($path, '.md')] = ['kind' => $kind, 'group' => $group];
    }

    ksort($refs);

    return $refs;
}

/**
 * Nomes (sem .blade.php) dos exemplos de componentes em skill/examples/components.
 *
 * @return list<string>
 */
function skillLintComponentExampleNames(): array
{
    $names = [];

    foreach (glob(dirname(__DIR__, 2).'/skill/examples/components/*.blade.php') ?: [] as $path) {
        $names[] = basename($path, '.blade.php');
    }

    sort($names);

    return $names;
}

test('example does not use raw palette colors', function (string $file) {
    $content = (string) file_get_contents($file);

    preg_match_all(SKILL_EXAMPLE_RAW_PALETTE, $content, $matches);

    expect($matches[0])->toBe([], basename($file).' usa cor de paleta crua: '.implode(', ', array_unique($matches[0])));
})->with(fn () => skillLintExampleFiles());

test('example does not use dark: variants', function (string $file) {
    $content = (string) file_get_contents($file);

    expect(preg_match('/\bdark:/', $content))->toBe(0, basename($file).' usa a variante dark:');
})->with(fn () => skillLintExampleFiles());

test('icon-only x-ui::button in examples has aria-label or tooltip', function (string $file) {
    $content = (string) file_get_contents($file);

    // Self-closing ou com corpo vazio (sem slot de texto).
    $pattern = '/<x-ui::button\b([^>]*)(?:\/>|>\s*<\/x-ui::button>)/s';
    preg_match_all($pattern, $content, $matches);

    $violations = [];

    foreach ($matches[1] as $attributes) {
        if (! preg_match('/\sicon=/', $attributes)) {
            continue;
        }

        $labelled = preg_match('/\saria-label=/', $attributes) || preg_match('/\stooltip\b/', $attributes);

        if (! $labelled) {
            $violations[] = '<x-ui::button'.$attributes.'>';
        }
    }

    expect($violations)->toBe([], basename($file).' tem botão só-ícone sem aria-label/tooltip');
})->with(fn () => skillLintExampleFiles());

test('every covered component reference has a matching example', function () {
    $refs = skillLintReferences();
    $examples = skillLintComponentExampleNames();

    $missing = [];

    foreach ($refs as $name => $meta) {
        if ($meta['kind'] !== 'component' || ! in_array($meta['group'], SKILL_EXAMPLE_COVERED_GROUPS, true)) {
            continue;
        }

        if (! in_array($name, $examples, true)) {
            $missing[] = $name;
        }
    }

    expect($missing)->toBe([], 'references sem exemplo em examples/components: '.implode(', ', $missing));
});

test('every component example has a covered component reference', function () {
    $refs = skillLintReferences();

    $orphans = [];

    foreach (skillLintComponentExampleNames() as $name) {
        $meta = $refs[$name] ?? null;

        if ($meta === null || $meta['kind'] !== 'component') {
            $orphans[] = $name;
        }
    }

    expect($orphans)->toBe([], 'exemplos sem reference kind: component: '.implode(', ', $orphans));
});
