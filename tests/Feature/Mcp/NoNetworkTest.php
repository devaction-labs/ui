<?php

it('S9 o código MCP/skill não faz chamadas de rede nem referencia URLs', function () {
    $root = dirname(__DIR__, 3);
    $files = [
        $root.'/src/Support/BladeComponentParser.php',
        $root.'/src/Support/ComponentManifest.php',
        $root.'/src/Support/SkillMirror.php',
        $root.'/src/Console/SkillCommand.php',
    ];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/src/Mcp', FilesystemIterator::SKIP_DOTS)) as $f) {
        if ($f->getExtension() === 'php') {
            $files[] = $f->getPathname();
        }
    }

    $hits = [];

    foreach ($files as $file) {
        foreach ((file($file) ?: []) as $n => $line) {
            if (str_contains($line, 'json-schema.org')) {
                continue;
            }

            if (preg_match('/Http::|curl_|fsockopen|stream_socket_client|file_get_contents\(\s*[\'"]https?:|https?:\/\//', $line)) {
                $hits[] = substr($file, strlen($root) + 1).':'.($n + 1);
            }
        }
    }

    expect($hits)->toBe([]);
});
