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
 * Cria os arquivos de um template ou layout novo e roda ui:skill.
 */
#[Name('scaffold-template')]
#[Description('Authoring (kit checkout only): scaffolds a new page template (kind=template) or layout shell (kind=layout) with blade, Pest test and example (templates also get a skill reference and index line). Never overwrites; all-or-nothing. dry_run defaults to true.')]
class ScaffoldTemplateTool extends ScaffoldTool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('kebab-case name, optionally one dot level.')->required(),
            'kind' => $schema->string()->enum(['template', 'layout'])->required(),
            'summary' => $schema->string()->description('One-line summary.')->required(),
            'dry_run' => $schema->boolean()->default(true)->description('true (default) only plans; false writes the files.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:'.Scaffolder::NAME],
            'kind' => ['required', Rule::in(['template', 'layout'])],
            'group' => ['prohibited'],
            'summary' => ['required', 'string', 'max:200', 'regex:/\\S/'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);

        return $this->run($data['kind'], $data['name'], $data, (bool) ($data['dry_run'] ?? true));
    }
}
