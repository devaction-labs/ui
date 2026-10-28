<?php

namespace DevAction\Ui\Mcp\Tools;

use DevAction\Ui\Support\ComponentManifest;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

/**
 * Devolve a entrada do manifesto de um componente, com o exemplo e a reference em markdown.
 */
#[Name('get-component')]
#[Description('Returns one component: props (with defaults), attributes, slots, children, example markup and reference markdown. Accepts a tag name (table.row) or a reference name (data-table).')]
#[IsReadOnly]
#[IsIdempotent]
class GetComponentTool extends Tool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Component name (button, table.row) or reference name (data-table).')->required(),
        ];
    }

    public function handle(Request $request): Response
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);

        $manifest = new ComponentManifest;
        $component = $manifest->find($data['name']);

        if ($component === null) {
            return Response::error($this->unknown($manifest, $data['name']));
        }

        $examplePath = $manifest->examplePath($data['name']);
        $referencePath = dirname(__DIR__, 3).'/'.$component['reference'];

        $component['example'] = $examplePath === null ? null : (string) file_get_contents($examplePath);
        $component['reference'] = is_file($referencePath) ? (string) file_get_contents($referencePath) : null;

        return Response::json($component);
    }

    /**
     * Mensagem de erro com até 3 sugestões por distância de Levenshtein.
     */
    private function unknown(ComponentManifest $manifest, string $name): string
    {
        $needle = strtolower(trim($name));
        $distances = [];

        foreach ($manifest->load()->search() as $component) {
            $distances[$component['name']] = levenshtein($needle, $component['name']);
        }

        asort($distances);
        $suggestions = array_slice(array_keys($distances), 0, 3);

        return sprintf('Unknown component "%s". Did you mean: %s', $name, implode(', ', $suggestions));
    }
}
