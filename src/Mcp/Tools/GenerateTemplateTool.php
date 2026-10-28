<?php

namespace DevAction\Ui\Mcp\Tools;

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
 * Devolve o Blade de uma página ou layout do kit (exemplo validado), com marca opcional e classe Livewire nas telas de auth.
 */
#[Name('generate-template')]
#[Description('Returns a full page/layout Blade file from the kit. mode=livewire only for auth.* templates (adds the Livewire class outline). brand replaces the placeholder brand "Acme".')]
#[IsReadOnly]
#[IsIdempotent]
class GenerateTemplateTool extends Tool
{
    /**
     * Template => arquivo do exemplo (sem a variante livewire).
     */
    private const FILES = [
        'auth.login' => 'templates/auth-login',
        'auth.register' => 'templates/auth-register',
        'auth.magic-link' => 'templates/auth-magic-link',
        'auth.reset-password' => 'templates/auth-reset-password',
        'pricing' => 'templates/pricing',
        'layout.classic' => 'layouts/classic',
        'layout.rail' => 'layouts/rail',
        'layout.topbar' => 'layouts/topbar',
        'layout.split' => 'layouts/split',
        'layout.centered' => 'layouts/centered',
        'layout.docs' => 'layouts/docs',
    ];

    public function schema(JsonSchema $schema): array
    {
        return [
            'template' => $schema->string()->enum(array_keys(self::FILES))->description('Template or layout to generate.')->required(),
            'mode' => $schema->string()->enum(['post', 'livewire'])->default('post')->description('livewire is only available for auth.* templates.'),
            'brand' => $schema->string()->description('Replaces the placeholder brand "Acme".'),
        ];
    }

    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'template' => ['required', Rule::in(array_keys(self::FILES))],
            'mode' => ['sometimes', Rule::in(['post', 'livewire'])],
            'brand' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        $livewire = ($data['mode'] ?? 'post') === 'livewire';

        if ($livewire && ! str_starts_with($data['template'], 'auth.')) {
            return Response::error('mode "livewire" is only available for auth.* templates');
        }

        $path = dirname(__DIR__, 3).'/skill/examples/'.self::FILES[$data['template']].($livewire ? '.livewire' : '').'.blade.php';

        if (! is_file($path)) {
            return Response::error(sprintf('Example file for "%s" not found.', $data['template']));
        }

        $lines = explode("\n", (string) file_get_contents($path));
        $class = $livewire && preg_match('/\{\{-- Livewire class: (.*?) --\}\}/', $lines[1] ?? '', $match) ? $match[1] : null;
        $body = implode("\n", array_slice($lines, $class === null ? 1 : 2));

        if (isset($data['brand']) && $data['brand'] !== '') {
            $body = str_replace('="Acme"', '="'.e($data['brand']).'"', $body);
        }

        return Response::text($class === null ? $body : rtrim($body)."\n\n".$class."\n");
    }
}
