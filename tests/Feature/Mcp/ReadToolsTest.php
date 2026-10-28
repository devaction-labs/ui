<?php

use DevAction\Ui\Mcp\Tools\GetComponentTool;
use DevAction\Ui\Mcp\Tools\ListComponentsTool;
use DevAction\Ui\Mcp\UiServer;
use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::preventStrayRequests());

it('filtra list-components por grupo', function () {
    UiServer::tool(ListComponentsTool::class, ['group' => 'Form'])
        ->assertOk()
        ->assertSee('combobox')
        ->assertDontSee('x-ui::modal');
});

it('filtra list-components por texto sem caixa', function () {
    UiServer::tool(ListComponentsTool::class, ['query' => 'DATA'])
        ->assertOk()
        ->assertSee('table');
});

it('recusa grupo fora do enum', function () {
    UiServer::tool(ListComponentsTool::class, ['group' => 'Xyz'])->assertHasErrors();
});

it('lista só campos enxutos', function () {
    UiServer::tool(ListComponentsTool::class, ['group' => 'Base'])
        ->assertSee(['"tag"', '"kind"', '"summary"'])
        ->assertDontSee('"props"');
});

it('get-component devolve props, exemplo e reference', function () {
    UiServer::tool(GetComponentTool::class, ['name' => 'button'])
        ->assertOk()
        ->assertSee('"variant"')
        ->assertSee('outline')
        ->assertSee('"example"')
        ->assertSee('"reference"');
});

it('get-component resolve o nome da reference', function () {
    UiServer::tool(GetComponentTool::class, ['name' => 'data-table'])
        ->assertOk()
        ->assertSee('x-ui::table');
});

it('get-component sugere nomes parecidos', function () {
    UiServer::tool(GetComponentTool::class, ['name' => 'buton'])
        ->assertHasErrors(['Unknown component "buton". Did you mean: button']);
});

it('get-component exige name', function () {
    UiServer::tool(GetComponentTool::class, [])->assertHasErrors();
});
