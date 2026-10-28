<?php

namespace DevAction\Ui\Support;

use Illuminate\Support\Str;

/**
 * Manifesto dos componentes: junta o parser dos .blade.php com o front matter das references da skill
 * e grava/lê resources/ui-manifest.json. Fonte única para o servidor MCP e para os testes.
 */
class ComponentManifest
{
    public const FILE = 'resources/ui-manifest.json';

    public const JSON_FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    /**
     * Manifestos já lidos do disco, por raiz.
     *
     * @var array<string, list<array<string, mixed>>>
     */
    private static array $loaded = [];

    /**
     * Assinatura (mtime:size) do arquivo no momento da leitura, por raiz.
     *
     * @var array<string, string>
     */
    private static array $signatures = [];

    private readonly string $root;

    public function __construct(?string $root = null)
    {
        $this->root = rtrim($root ?? dirname(__DIR__, 2), '/');
    }

    /**
     * Esquece o que foi lido do disco (após regravar o JSON).
     */
    public static function flush(): void
    {
        self::$loaded = [];
        self::$signatures = [];
    }

    /**
     * Monta o manifesto a partir dos arquivos-fonte, sem ler o JSON gravado.
     *
     * @return array{version: int, components: list<array<string, mixed>>}
     */
    public function build(): array
    {
        $componentsRoot = $this->root.'/resources/views/components';
        $owners = $this->owners();
        $parser = new BladeComponentParser;
        $components = [];

        foreach ($this->bladeFiles($componentsRoot) as $path) {
            $parsed = $parser->parse($path, $componentsRoot);
            $owner = $owners[$parsed['name']] ?? null;

            if ($owner === null) {
                continue;
            }

            $parsed['file'] = 'resources/views/components/'.$parsed['file'];
            $parsed['kind'] = $owner['kind'];
            $parsed['group'] = $owner['group'];
            $parsed['summary'] = $owner['summary'];
            $parsed['reference'] = $owner['reference'];
            $parsed['example'] = $owner['example'];
            $components[] = $this->sortKeys($parsed);
        }

        usort($components, fn (array $a, array $b): int => strcmp($a['name'], $b['name']));

        return ['version' => 1, 'components' => $components];
    }

    /**
     * O manifesto como gravado em resources/ui-manifest.json (bytes exatos).
     */
    public function json(): string
    {
        return json_encode($this->build(), self::JSON_FLAGS)."\n";
    }

    public function path(): string
    {
        return $this->root.'/'.self::FILE;
    }

    /**
     * Lê o JSON gravado (uma vez por raiz).
     */
    public function load(): self
    {
        $path = $this->path();
        clearstatcache(true, $path);
        $signature = is_file($path) ? filemtime($path).':'.filesize($path) : 'ausente';

        if (! isset(self::$loaded[$this->root]) || (self::$signatures[$this->root] ?? null) !== $signature) {
            self::$signatures[$this->root] = $signature;
            $data = is_file($this->path()) ? json_decode((string) file_get_contents($this->path()), true) : null;
            self::$loaded[$this->root] = is_array($data) && is_array($data['components'] ?? null) ? array_values($data['components']) : [];
        }

        return $this;
    }

    /**
     * Componente pelo nome (ex.: table.row) ou pelo nome da reference (ex.: data-table).
     *
     * @return array<string, mixed>|null
     */
    public function find(string $nameOrReference): ?array
    {
        $components = $this->load()->components();
        $needle = Str::lower(trim($nameOrReference));

        foreach ($components as $component) {
            if ($component['name'] === $needle) {
                return $component;
            }
        }

        foreach ($components as $component) {
            if (basename((string) $component['reference'], '.md') === $needle) {
                return $component;
            }
        }

        return null;
    }

    /**
     * Componentes filtrados por grupo e por texto (sem acento e sem diferença de caixa).
     *
     * @return list<array<string, mixed>>
     */
    public function search(?string $group = null, ?string $query = null): array
    {
        $query = $query === null ? '' : Str::ascii(Str::lower(trim($query)));

        return array_values(array_filter($this->load()->components(), function (array $component) use ($group, $query): bool {
            if ($group !== null && $group !== '' && $component['group'] !== $group) {
                return false;
            }

            if ($query === '') {
                return true;
            }

            $haystack = Str::ascii(Str::lower($component['name'].' '.$component['summary'].' '.$component['group']));

            return str_contains($haystack, $query);
        }));
    }

    /**
     * Caminho absoluto do exemplo do componente, ou null quando não há.
     */
    public function examplePath(string $nameOrReference): ?string
    {
        $example = $this->find($nameOrReference)['example'] ?? null;

        return is_string($example) && is_file($this->root.'/'.$example) ? $this->root.'/'.$example : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function components(): array
    {
        return self::$loaded[$this->root] ?? [];
    }

    /**
     * Dono de cada componente: a reference que o lista em `components:`.
     *
     * @return array<string, array{kind: string, group: string, summary: string, reference: string, example: string|null}>
     */
    private function owners(): array
    {
        $owners = [];
        $files = glob($this->root.'/skill/references/*.md') ?: [];
        sort($files);

        foreach ($files as $file) {
            $content = (string) file_get_contents($file);
            $meta = self::frontMatter($content);

            if ($meta === null || ! is_array($meta['components'] ?? null)) {
                continue;
            }

            $base = basename($file, '.md');
            $kind = is_string($meta['kind'] ?? null) ? $meta['kind'] : 'component';
            $example = match ($kind) {
                'template' => 'skill/examples/templates/'.$base.'.blade.php',
                'layout' => 'skill/examples/layouts/'.$base.'.blade.php',
                default => 'skill/examples/components/'.$base.'.blade.php',
            };

            foreach ($meta['components'] as $component) {
                $owners[$component] = [
                    'kind' => $kind,
                    'group' => is_string($meta['group'] ?? null) ? $meta['group'] : '',
                    'summary' => self::summary($content),
                    'reference' => 'skill/references/'.$base.'.md',
                    'example' => is_file($this->root.'/'.$example) ? $example : null,
                ];
            }
        }

        return $owners;
    }

    /**
     * Front matter YAML plano (chaves simples e lista inline `[a, b]`); null quando ausente ou malformado.
     *
     * @return array<string, string|list<string>>|null
     */
    public static function frontMatter(string $content): ?array
    {
        if (! preg_match('/\A---\R(.*?)\R---\R/s', $content, $match)) {
            return null;
        }

        $data = [];

        foreach (preg_split('/\R/', $match[1]) ?: [] as $line) {
            if (trim($line) === '') {
                continue;
            }

            if (! preg_match('/^([a-z_]+):\s*(.*?)\s*(?:#.*)?$/', $line, $pair)) {
                return null;
            }

            $value = $pair[2];

            if (str_starts_with($value, '[')) {
                if (! str_ends_with($value, ']')) {
                    return null;
                }

                $inner = trim(substr($value, 1, -1));
                $data[$pair[1]] = $inner === '' ? [] : array_map('trim', explode(',', $inner));
            } else {
                $data[$pair[1]] = $value;
            }
        }

        return $data;
    }

    /**
     * Primeiro parágrafo depois do `# título` da reference.
     */
    private static function summary(string $content): string
    {
        $body = (string) preg_replace('/\A---\R.*?\R---\R/s', '', $content);

        if (! preg_match('/^# [^\r\n]*\R+([^\r\n]+(?:\R[^\r\n]+)*)/m', $body, $match)) {
            return '';
        }

        return trim((string) preg_replace('/\s+/', ' ', $match[1]));
    }

    /**
     * @return list<string>
     */
    private function bladeFiles(string $componentsRoot): array
    {
        if (! is_dir($componentsRoot)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($componentsRoot, \FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $file) {
            $relative = substr((string) $file, strlen($componentsRoot) + 1);

            if (str_ends_with($relative, '.blade.php') && ! str_starts_with($relative, 'icons/')) {
                $files[] = (string) $file;
            }
        }

        sort($files);

        return $files;
    }

    /**
     * Ordena as chaves de arrays associativos, em qualquer profundidade (listas mantêm a ordem).
     *
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    private function sortKeys(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sortKeys($value);
            }
        }

        if (! array_is_list($data)) {
            ksort($data, SORT_STRING);
        }

        return $data;
    }
}
