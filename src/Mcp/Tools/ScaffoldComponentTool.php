<?php

namespace DevAction\Ui\Mcp\Tools;

use DevAction\Ui\Mcp\Scaffolder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;

/**
 * Cria os arquivos de um componente novo (Blade, teste Pest, reference, exemplo, linha no índice) e roda ui:skill.
 */
#[Name('scaffold-component')]
#[Description('Authoring (kit checkout only): scaffolds a new component — blade, Pest test, skill reference, example and SKILL.md index line. Never overwrites; all-or-nothing. dry_run defaults to true and returns the planned files; pass dry_run=false to write.')]
class ScaffoldComponentTool extends ScaffoldTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('kebab-case component name, optionally one dot level: button, table.row.')->required(),
            'group' => $schema->string()->enum(Scaffolder::GROUPS)->description('Index group in SKILL.md.')->required(),
            'summary' => $schema->string()->description('One-line summary.')->required(),
            'props' => $schema->array()->description('Props: [{"name":"variant","type":"string","default":"base","description":"…"}].'),
            'children' => $schema->array()->items($schema->string())->description('Child component names (become {name}/{child}.blade.php).'),
            'dry_run' => $schema->boolean()->default(true)->description('true (default) only plans; false writes the files.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:'.Scaffolder::NAME],
            'group' => ['required', Rule::in(Scaffolder::GROUPS)],
            'summary' => ['required', 'string', 'max:200', 'regex:/\\S/'],
            'props' => ['sometimes', 'array', 'max:30'],
            'props.*.name' => ['required', 'string', 'regex:/^[a-z][A-Za-z0-9]*$/'],
            'props.*.type' => ['sometimes', 'string', 'regex:/^[a-z|]{1,30}$/'],
            'props.*.default' => ['sometimes', 'nullable', 'string', 'max:60'],
            'props.*.description' => ['sometimes', 'nullable', 'string', 'max:120'],
            'children' => ['sometimes', 'array', 'max:20'],
            'children.*' => ['string', 'regex:'.Scaffolder::NAME],
            'dry_run' => ['sometimes', 'boolean'],
        ]);

        return $this->run('component', $data['name'], $data, (bool) ($data['dry_run'] ?? true));
    }
}
