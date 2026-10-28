<?php

use DevAction\Ui\Mcp\UiServer;
use Illuminate\Support\Facades\Http;
use Laravel\Mcp\Server\Registrar;

beforeEach(fn () => Http::preventStrayRequests());

it('registra o servidor local devaction-ui quando laravel/mcp existe', function () {
    expect(app(Registrar::class)->getLocalServer('devaction-ui'))->not->toBeNull();
});

it('lista as seis tools no checkout do kit (quatro de consumo e duas de autoria)', function () {
    $names = array_map(fn (string $tool): string => app($tool)->name(), (new ReflectionClass(UiServer::class))->getDefaultProperties()['tools']);

    expect($names)->toBe(['list-components', 'get-component', 'generate-component', 'generate-template', 'scaffold-component', 'scaffold-template']);
});

it('não importa Laravel\Mcp no provider e guarda o registro com class_exists', function () {
    $source = file_get_contents(dirname(__DIR__, 3).'/src/UiServiceProvider.php');

    expect($source)->not->toContain('use Laravel\Mcp')
        ->and($source)->toContain("class_exists('Laravel\\Mcp\\Facades\\Mcp')");
});
