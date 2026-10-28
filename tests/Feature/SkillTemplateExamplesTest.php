<?php

/**
 * Pareamento dos exemplos de templates e layouts da skill (T-008).
 * Lista fixa: 9 templates (5 POST + 4 Livewire) e 6 layouts.
 */
const SKILL_TEMPLATE_FILES = [
    'templates/auth-login.blade.php',
    'templates/auth-register.blade.php',
    'templates/auth-magic-link.blade.php',
    'templates/auth-reset-password.blade.php',
    'templates/pricing.blade.php',
    'templates/auth-login.livewire.blade.php',
    'templates/auth-register.livewire.blade.php',
    'templates/auth-magic-link.livewire.blade.php',
    'templates/auth-reset-password.livewire.blade.php',
];

const SKILL_LAYOUT_FILES = [
    'layouts/classic.blade.php',
    'layouts/rail.blade.php',
    'layouts/topbar.blade.php',
    'layouts/split.blade.php',
    'layouts/centered.blade.php',
    'layouts/docs.blade.php',
];

/**
 * @return array<string, array{0: string}>
 */
function skillTemplateAndLayoutFiles(): array
{
    $root = dirname(__DIR__, 2).'/skill/examples';
    $files = [];

    foreach (['templates', 'layouts'] as $dir) {
        foreach (glob($root.'/'.$dir.'/*.blade.php') ?: [] as $path) {
            $files[$dir.'/'.basename($path)] = [$path];
        }
    }

    ksort($files);

    return $files;
}

/**
 * Nomes de componentes do kit que recebem a prop brand (ex.: auth.layout).
 *
 * @return list<string>
 */
function skillBrandComponentNames(): array
{
    $dir = dirname(__DIR__, 2).'/resources/views/components';
    $names = [];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($iterator as $file) {
        if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }

        if (str_contains((string) file_get_contents($file->getPathname()), "'brand' =>")) {
            $relative = substr($file->getPathname(), strlen($dir) + 1, -strlen('.blade.php'));
            $names[] = str_replace('/', '.', $relative);
        }
    }

    sort($names);

    return $names;
}

test('the fixed list of 9 template and 6 layout examples exists', function () {
    $root = dirname(__DIR__, 2).'/skill/examples';

    expect(SKILL_TEMPLATE_FILES)->toHaveCount(9)
        ->and(SKILL_LAYOUT_FILES)->toHaveCount(6);

    $missing = [];

    foreach (array_merge(SKILL_TEMPLATE_FILES, SKILL_LAYOUT_FILES) as $relative) {
        if (! is_file($root.'/'.$relative)) {
            $missing[] = $relative;
        }
    }

    expect($missing)->toBe([], 'exemplos ausentes: '.implode(', ', $missing));
});

test('every file in templates and layouts is in the fixed list', function () {
    $expected = array_merge(SKILL_TEMPLATE_FILES, SKILL_LAYOUT_FILES);
    $actual = array_keys(skillTemplateAndLayoutFiles());

    $unexpected = array_values(array_diff($actual, $expected));

    expect($unexpected)->toBe([], 'arquivos fora da lista fixa: '.implode(', ', $unexpected));
});

test('every template or layout example uses brand="Acme" on components with a brand prop', function (string $file) {
    $content = (string) file_get_contents($file);
    $brandNames = skillBrandComponentNames();

    $violations = [];

    foreach ($brandNames as $name) {
        $tag = 'x-ui::'.preg_quote($name, '/');
        preg_match_all('/<'.$tag.'\b[^>]*>/s', $content, $tags);

        foreach ($tags[0] as $openTag) {
            if (! preg_match('/\sbrand="Acme"/', $openTag)) {
                $violations[] = $openTag;
            }
        }
    }

    expect($violations)->toBe([], basename($file).' tem componente com brand sem brand="Acme"');
})->with(fn () => skillTemplateAndLayoutFiles());
