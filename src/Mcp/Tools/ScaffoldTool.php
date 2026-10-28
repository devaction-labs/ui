<?php

namespace DevAction\Ui\Mcp\Tools;

use DevAction\Ui\Mcp\Scaffolder;
use InvalidArgumentException;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use RuntimeException;

/**
 * Base das tools de autoria: só registra no checkout do kit e só grava com dry_run=false.
 */
abstract class ScaffoldTool extends Tool
{
    /**
     * Chamado por laravel/mcp (eligibleForRegistration): fora do checkout (vendor/ ou sem tests/TestCase.php) a tool some.
     */
    public function shouldRegister(): bool
    {
        return app(Scaffolder::class)->isKitCheckout();
    }

    /**
     * @param  'component'|'template'|'layout'  $kind
     * @param  array<string, mixed>  $input
     */
    protected function run(string $kind, string $name, array $input, bool $dryRun): Response
    {
        $scaffolder = app(Scaffolder::class);

        try {
            $plan = $scaffolder->plan($kind, $name, $input);

            if ($dryRun) {
                return Response::json(['planned' => array_map(fn (array $file): array => ['path' => $file['path'], 'contents' => $file['contents']], $plan)]);
            }

            $created = $scaffolder->write($plan);
            $scaffolder->sync();
        } catch (RuntimeException $e) {
            return Response::error($e->getMessage());
        } catch (InvalidArgumentException $e) {
            return Response::error($e->getMessage());
        }

        return Response::json([
            'created' => $created,
            'next_steps' => [sprintf("add '%s' to the GenerateTemplateTool map (src/Mcp/Tools/GenerateTemplateTool.php)", $kind === 'layout' ? 'layout.'.$name : $name)],
        ]);
    }
}
