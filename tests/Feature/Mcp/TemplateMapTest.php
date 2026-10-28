<?php

use DevAction\Ui\Mcp\Tools\GenerateTemplateTool;
use DevAction\Ui\Mcp\UiServer;

/**
 * @return array<int, string>
 */
function templateEnum(): array
{
    /** @var array<int, string> */
    return (new GenerateTemplateTool)->toArray()['inputSchema']['properties']['template']['enum'] ?? [];
}

it('todo valor do enum de generate-template gera um exemplo existente', function () {
    expect(templateEnum())->not->toBeEmpty();

    foreach (templateEnum() as $value) {
        UiServer::tool(GenerateTemplateTool::class, ['template' => $value])->assertOk();
    }
});

it('todo exemplo de template e layout corresponde a um valor do enum', function () {
    $root = dirname(__DIR__, 3).'/skill/examples';
    $enum = templateEnum();
    $names = [];

    foreach ((glob($root.'/templates/*.blade.php') ?: []) as $file) {
        if (! str_ends_with($file, '.livewire.blade.php')) {
            $names[] = preg_replace('/^auth-/', 'auth.', basename($file, '.blade.php'));
        }
    }

    foreach ((glob($root.'/layouts/*.blade.php') ?: []) as $file) {
        $names[] = 'layout.'.basename($file, '.blade.php');
    }

    expect($names)->not->toBeEmpty()->and(array_diff($names, $enum))->toBe([]);
});
