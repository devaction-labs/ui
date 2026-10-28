<?php

namespace DevAction\Ui\Mcp;

use DevAction\Ui\Mcp\Tools\GenerateComponentTool;
use DevAction\Ui\Mcp\Tools\GenerateTemplateTool;
use DevAction\Ui\Mcp\Tools\GetComponentTool;
use DevAction\Ui\Mcp\Tools\ListComponentsTool;
use DevAction\Ui\Mcp\Tools\ScaffoldComponentTool;
use DevAction\Ui\Mcp\Tools\ScaffoldTemplateTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

/**
 * Servidor MCP local (stdio) do DevAction UI: lê o manifesto e as references do próprio pacote, sem rede.
 */
#[Name('DevAction UI')]
#[Version('0.3.0')]
#[Instructions('Use list-components and get-component before writing x-ui markup: they return the real props, slots, example and reference of every <x-ui::*> component. Never invent props.')]
class UiServer extends Server
{
    /**
     * @var array<int, class-string<Server\Tool>>
     */
    protected array $tools = [
        ListComponentsTool::class,
        GetComponentTool::class,
        GenerateComponentTool::class,
        GenerateTemplateTool::class,
        ScaffoldComponentTool::class,
        ScaffoldTemplateTool::class,
    ];
}
