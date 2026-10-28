<?php

namespace DevAction\Ui\Mcp\Tools;

use DevAction\Ui\Mcp\MarkupBuilder;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Blade;
use InvalidArgumentException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Throwable;

/**
 * Gera o markup <x-ui::…> de um componente, validando props contra o manifesto e a compilação Blade.
 */
#[Name('generate-component')]
#[Description('Generates ready-to-paste <x-ui::…> markup for one component. Props are validated against the manifest (typos are rejected); true becomes a bare attribute, false/numbers become :prop, strings starting with $ become bound PHP expressions (:prop="$value"). slot/slots are inserted as raw Blade, not escaped: trusted input only (intentional).')]
#[IsReadOnly]
#[IsIdempotent]
class GenerateComponentTool extends Tool
{
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Component name (button, table.row) or reference name.')->required(),
            'props' => $schema->object()->description('Props and attributes: {"variant":"primary","flyout":true}. camelCase keys become kebab-case.'),
            'slot' => $schema->string()->description('Default slot content.'),
            'slots' => $schema->object()->description('Named slots: {"actions":"<x-ui::button>Edit</x-ui::button>"}.'),
        ];
    }

    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'props' => ['sometimes', 'array', 'max:50'],
            'props.*' => [fn (string $attribute, mixed $value, \Closure $fail) => is_scalar($value) ? null : $fail('Each prop must be a string, number or boolean.'), fn (string $attribute, mixed $value, \Closure $fail) => is_string($value) && mb_strlen($value) > 2000 ? $fail('Each prop value may have at most 2000 characters.') : null],
            'slot' => ['sometimes', 'nullable', 'string', 'max:20000'],
            'slots' => ['sometimes', 'array', 'max:20'],
            'slots.*' => ['string', 'max:20000'],
        ]);

        try {
            $markup = (new MarkupBuilder)->build($data['name'], $data['props'] ?? [], $data['slot'] ?? null, $data['slots'] ?? []);
        } catch (InvalidArgumentException $e) {
            return Response::error($e->getMessage());
        }

        try {
            // compileString só traduz as diretivas; TOKEN_PARSE acusa o PHP inválido (ex.: @if sem @endif).
            token_get_all(Blade::compileString($markup), TOKEN_PARSE);
        } catch (Throwable $e) {
            return Response::error('Generated markup does not compile: '.$e->getMessage());
        }

        return Response::text($markup);
    }
}
