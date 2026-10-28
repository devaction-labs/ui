<?php

namespace DevAction\Ui\Mcp\Tools;

use DevAction\Ui\Support\ComponentManifest;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

/**
 * Lista os componentes do kit (sem a documentação), com filtro por grupo e por texto.
 */
#[Name('list-components')]
#[Description('Lists the DevAction UI components as [{name, tag, kind, group, summary}], optionally filtered by group and by text. Use get-component for props, slots and examples.')]
#[IsReadOnly]
#[IsIdempotent]
class ListComponentsTool extends Tool
{
    public const GROUPS = ['Base', 'Overlay', 'Form', 'Navigation', 'Data', 'AI', 'Primitives', 'Templates', 'Layouts'];

    public function schema(JsonSchema $schema): array
    {
        return [
            'group' => $schema->string()->enum(self::GROUPS)->description('Component group.'),
            'query' => $schema->string()->description('Text searched in the name and summary (accent and case insensitive).'),
        ];
    }

    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'group' => ['nullable', 'string', Rule::in(self::GROUPS)],
            'query' => ['nullable', 'string', 'max:200'],
        ]);

        $components = (new ComponentManifest)->search($data['group'] ?? null, $data['query'] ?? null);

        return Response::json(array_map(fn (array $component): array => [
            'name' => $component['name'],
            'tag' => $component['tag'],
            'kind' => $component['kind'],
            'group' => $component['group'],
            'summary' => $component['summary'],
        ], $components));
    }
}
