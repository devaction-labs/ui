<?php

use Illuminate\Support\Facades\Blade;

/**
 * Lista todo exemplo de skill/examples (recursivo), para o dataset de renderização.
 *
 * @return array<string, array{0: string}>
 */
function skillExamplesFiles(): array
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
            $relative = substr($path, strlen($root) + 1);
            $files[$relative] = [$path];
        }
    }

    ksort($files);

    return $files;
}

test('every skill example renders with Blade::render in en without exception', function (string $file) {
    app()->setLocale('en');

    $html = Blade::render((string) file_get_contents($file));

    expect(trim($html))->not->toBe('');
})->with(fn () => skillExamplesFiles());

test('the examples dataset is not empty', function () {
    expect(skillExamplesFiles())->not->toBeEmpty();
});
