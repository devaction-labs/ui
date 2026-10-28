<?php

use DevAction\Ui\Mcp\Scaffolder;
use DevAction\Ui\Mcp\Tools\GenerateComponentTool;
use DevAction\Ui\Mcp\Tools\GenerateTemplateTool;
use DevAction\Ui\Mcp\Tools\GetComponentTool;
use DevAction\Ui\Mcp\Tools\ListComponentsTool;
use DevAction\Ui\Mcp\Tools\ScaffoldComponentTool;
use DevAction\Ui\Mcp\Tools\ScaffoldTemplateTool;
use Illuminate\Support\Facades\Http;
use Laravel\Mcp\Server\Tool;

beforeEach(fn () => Http::preventStrayRequests());

/**
 * @param  class-string<Tool>  $class
 * @return array<string, mixed>
 */
function toolArray(string $class): array
{
    return (new $class)->toArray();
}

/**
 * @param  class-string<Tool>  $class
 * @return array<string, mixed>
 */
function toolSchema(string $class): array
{
    $array = toolArray($class);

    return is_array($array['inputSchema'] ?? null) ? $array['inputSchema'] : [];
}

it('list-components expõe group (enum) e query, sem required', function () {
    $schema = toolSchema(ListComponentsTool::class);

    expect($schema['properties'])->toHaveKeys(['group', 'query'])
        ->and($schema['properties']['group']['enum'])->toBe(['Base', 'Overlay', 'Form', 'Navigation', 'Data', 'AI', 'Primitives', 'Templates', 'Layouts'])
        ->and($schema['properties']['query']['type'])->toBe('string')
        ->and($schema['required'] ?? [])->toBe([]);
});

it('get-component exige name string', function () {
    $schema = toolSchema(GetComponentTool::class);

    expect(array_keys($schema['properties']))->toBe(['name'])
        ->and($schema['properties']['name']['type'])->toBe('string')
        ->and($schema['required'])->toBe(['name']);
});

it('generate-component exige name e aceita props/slot/slots', function () {
    $schema = toolSchema(GenerateComponentTool::class);

    expect(array_keys($schema['properties']))->toBe(['name', 'props', 'slot', 'slots'])
        ->and($schema['required'])->toBe(['name'])
        ->and($schema['properties']['props']['type'])->toBe('object')
        ->and($schema['properties']['slots']['type'])->toBe('object')
        ->and($schema['properties']['slot']['type'])->toBe('string');
});

it('generate-template exige template enum e mode default post', function () {
    $schema = toolSchema(GenerateTemplateTool::class);

    expect($schema['required'])->toBe(['template'])
        ->and($schema['properties']['template']['enum'])->toBe([
            'auth.login', 'auth.register', 'auth.magic-link', 'auth.reset-password', 'pricing',
            'layout.classic', 'layout.rail', 'layout.topbar', 'layout.split', 'layout.centered', 'layout.docs',
        ])
        ->and($schema['properties']['mode']['enum'])->toBe(['post', 'livewire'])
        ->and($schema['properties']['mode']['default'])->toBe('post')
        ->and($schema['properties']['brand']['type'])->toBe('string');
});

it('scaffold-component exige name, group e summary; dry_run default true; children array de string', function () {
    $schema = toolSchema(ScaffoldComponentTool::class);

    expect($schema['required'])->toBe(['name', 'group', 'summary'])
        ->and($schema['properties']['group']['enum'])->toBe(Scaffolder::GROUPS)
        ->and($schema['properties']['props']['type'])->toBe('array')
        ->and($schema['properties']['children']['type'])->toBe('array')
        ->and($schema['properties']['children']['items']['type'])->toBe('string')
        ->and($schema['properties']['dry_run']['type'])->toBe('boolean')
        ->and($schema['properties']['dry_run']['default'])->toBeTrue();
});

it('scaffold-template exige name, kind e summary; kind enum e dry_run default true', function () {
    $schema = toolSchema(ScaffoldTemplateTool::class);

    expect($schema['required'])->toBe(['name', 'kind', 'summary'])
        ->and($schema['properties']['kind']['enum'])->toBe(['template', 'layout'])
        ->and($schema['properties'])->not->toHaveKey('group')
        ->and($schema['properties']['summary']['type'])->toBe('string')
        ->and($schema['properties']['dry_run']['default'])->toBeTrue();
});

it('tools de consumo são read-only e idempotentes; autoria não tem annotations', function () {
    foreach ([ListComponentsTool::class, GetComponentTool::class, GenerateComponentTool::class, GenerateTemplateTool::class] as $class) {
        $annotations = (array) (toolArray($class)['annotations'] ?? []);
        expect($annotations)->toMatchArray(['readOnlyHint' => true, 'idempotentHint' => true]);
    }

    foreach ([ScaffoldComponentTool::class, ScaffoldTemplateTool::class] as $class) {
        expect((array) (toolArray($class)['annotations'] ?? []))->not->toHaveKey('readOnlyHint');
    }
});
