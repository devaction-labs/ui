<?php

use DevAction\Ui\Mcp\Tools\GenerateComponentTool;
use DevAction\Ui\Mcp\Tools\GenerateTemplateTool;
use DevAction\Ui\Mcp\UiServer;
use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::preventStrayRequests());

it('generate-component devolve o markup', function () {
    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'props' => ['variant' => 'primary', 'icon' => 'add-01'], 'slot' => 'New deal'])
        ->assertOk()
        ->assertSee('<x-ui::button variant="primary" icon="add-01">New deal</x-ui::button>');
});

it('generate-component erra com prop desconhecida', function () {
    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'props' => ['varient' => 'primary']])
        ->assertHasErrors(['variant']);
});

it('generate-component erra com componente desconhecido', function () {
    UiServer::tool(GenerateComponentTool::class, ['name' => 'nope'])->assertHasErrors(['Unknown component']);
});

it('generate-component erra quando o markup não compila', function () {
    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'slot' => "@if(true)\nx"])
        ->assertHasErrors(['does not compile']);
});

it('generate-component valida o schema', function () {
    UiServer::tool(GenerateComponentTool::class, [])->assertHasErrors();
});

it('generate-component recusa props acima do limite', function () {
    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'props' => ['title' => str_repeat('a', 2001)]])
        ->assertHasErrors();
    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'slot' => str_repeat('a', 20001)])
        ->assertHasErrors();
});

it('generate-component recusa 51 props (limite 50)', function () {
    $props = [];
    for ($i = 1; $i <= 51; $i++) {
        $props["data-k{$i}"] = 'v';
    }

    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'props' => $props])
        ->assertHasErrors(['props']);
});

it('generate-component aceita 50 props (limite 50)', function () {
    $props = [];
    for ($i = 1; $i <= 50; $i++) {
        $props["data-k{$i}"] = 'v';
    }

    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'props' => $props])
        ->assertOk()
        ->assertDontSee('must not have more than');
});

it('generate-component recusa 21 slots (limite 20)', function () {
    $slots = [];
    for ($i = 1; $i <= 21; $i++) {
        $slots["slot{$i}"] = 'x';
    }

    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'slots' => $slots])
        ->assertHasErrors(['slots']);
});

it('generate-component aceita 20 slots (limite 20)', function () {
    $slots = [];
    for ($i = 1; $i <= 20; $i++) {
        $slots["slot{$i}"] = 'x';
    }

    UiServer::tool(GenerateComponentTool::class, ['name' => 'button', 'slots' => $slots])
        ->assertOk()
        ->assertDontSee('must not have more than');
});

it('B11 auth.login livewire traz wire:submit e a classe', function () {
    UiServer::tool(GenerateTemplateTool::class, ['template' => 'auth.login', 'mode' => 'livewire'])
        ->assertOk()
        ->assertSee(['wire:submit="login"', 'model="form"', 'app/Livewire/Auth/Login.php', 'email', 'password', 'remember']);
});

it('B12 livewire fora de auth erra', function () {
    UiServer::tool(GenerateTemplateTool::class, ['template' => 'pricing', 'mode' => 'livewire'])
        ->assertHasErrors(['mode "livewire" is only available for auth.* templates']);
});

it('B13 brand troca só o valor Acme', function () {
    $raw = (string) file_get_contents(dirname(__DIR__, 3).'/skill/examples/layouts/classic.blade.php');
    $expected = implode("\n", array_slice(explode("\n", $raw), 1));

    UiServer::tool(GenerateTemplateTool::class, ['template' => 'layout.classic', 'brand' => 'Pedidos & Cia'])
        ->assertOk()
        ->assertSee('name="Pedidos &amp; Cia"')
        ->assertDontSee('="Acme"');

    expect(substr_count($expected, '="Acme"'))->toBe(1);
});

it('generate-template recusa template fora do enum', function () {
    UiServer::tool(GenerateTemplateTool::class, ['template' => 'nope'])->assertHasErrors();
});
