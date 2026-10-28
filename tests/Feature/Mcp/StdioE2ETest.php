<?php

/**
 * Sobe `testbench mcp:start devaction-ui` de verdade e conversa JSON-RPC por stdio (E1–E4).
 */
/**
 * @param  array<string, array{0: string, 1: mixed}>  $calls
 * @return array<string, array<string, mixed>>
 */
function mcpSession(array $calls): array
{
    $root = dirname(__DIR__, 3);
    $process = proc_open(
        [PHP_BINARY, 'vendor/bin/testbench', 'mcp:start', 'devaction-ui'],
        [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
        $pipes,
        $root,
    );

    if (! is_resource($process)) {
        throw new RuntimeException('Falha ao iniciar o servidor MCP.');
    }

    $responses = [];
    $buffer = '';

    $read = function (int $id) use (&$pipes, &$buffer, &$responses): array {
        $deadline = microtime(true) + 10;

        while (! isset($responses[$id])) {
            if (microtime(true) > $deadline) {
                throw new RuntimeException('Timeout aguardando resposta id='.$id);
            }

            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = trim(substr($buffer, 0, $pos));
                $buffer = substr($buffer, $pos + 1);
                $decoded = $line === '' ? null : json_decode($line, true);

                if (is_array($decoded) && isset($decoded['id'])) {
                    $responses[$decoded['id']] = $decoded;
                }
            }

            if (isset($responses[$id])) {
                break;
            }

            $r = [$pipes[1]];
            $w = $e = null;

            if (stream_select($r, $w, $e, 1) > 0) {
                $chunk = fread($pipes[1], 65536);

                if ($chunk === '' || $chunk === false) {
                    if (feof($pipes[1])) {
                        throw new RuntimeException('Servidor fechou stdout antes da resposta id='.$id);
                    }
                } else {
                    $buffer .= $chunk;
                }
            }
        }

        return $responses[$id];
    };

    $send = fn (array $message) => fwrite($pipes[0], json_encode($message)."\n");

    try {
        $send(['jsonrpc' => '2.0', 'id' => 1, 'method' => 'initialize', 'params' => [
            'protocolVersion' => '2025-03-26',
            'capabilities' => new stdClass,
            'clientInfo' => ['name' => 'pest-e2e', 'version' => '1.0'],
        ]]);
        $init = $read(1);
        $send(['jsonrpc' => '2.0', 'method' => 'notifications/initialized']);

        $out = ['init' => $init];
        $id = 2;

        foreach ($calls as $key => [$method, $params]) {
            $send(['jsonrpc' => '2.0', 'id' => $id, 'method' => $method, 'params' => $params]);
            $out[$key] = $read($id);
            $id++;
        }
    } finally {
        fclose($pipes[0]);
        stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);
    }

    return $out;
}

it('E1/E2 initialize e tools/list expõem exatamente as seis tools', function () {
    $r = mcpSession(['list' => ['tools/list', new stdClass]]);

    expect($r['init']['result']['serverInfo']['name'])->toBe('DevAction UI')
        ->and(array_column($r['list']['result']['tools'], 'name'))->toBe([
            'list-components', 'get-component', 'generate-component', 'generate-template', 'scaffold-component', 'scaffold-template',
        ]);
});

it('E3 generate-component devolve o markup exato', function () {
    $r = mcpSession(['call' => ['tools/call', [
        'name' => 'generate-component',
        'arguments' => ['name' => 'button', 'props' => ['variant' => 'primary'], 'slot' => 'Save'],
    ]]]);

    expect($r['call']['result']['content'][0]['text'])->toBe('<x-ui::button variant="primary">Save</x-ui::button>');
});

it('E4 scaffold-component sem dry_run só planeja e não escreve nada', function () {
    $root = dirname(__DIR__, 3);
    $file = $root.'/resources/views/components/e2e-probe.blade.php';
    $snapshot = function () use ($root): array {
        $files = [];

        foreach (['resources/views/components', 'skill', 'tests/Feature'] as $dir) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/'.$dir, FilesystemIterator::SKIP_DOTS)) as $f) {
                $files[substr($f->getPathname(), strlen($root))] = $f->getMTime();
            }
        }

        ksort($files);

        return $files;
    };
    $before = $snapshot();
    $r = mcpSession(['call' => ['tools/call', [
        'name' => 'scaffold-component',
        'arguments' => ['name' => 'e2e-probe', 'group' => 'Base', 'summary' => 'Probe'],
    ]]]);

    $payload = json_decode($r['call']['result']['content'][0]['text'], true);

    expect($payload['planned'])->toHaveCount(5)
        ->and(file_exists($file))->toBeFalse()
        ->and($snapshot())->toBe($before);
});
