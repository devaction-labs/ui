<?php

namespace DevAction\Ui\Mcp;

use DevAction\Ui\Support\ComponentManifest;
use DevAction\Ui\Support\SkillMirror;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

/**
 * Planeja e grava os arquivos de um componente, template ou layout novo — só dentro da raiz do pacote (checkout do kit).
 *
 * Segurança: o nome passa pela regex NAME antes de virar caminho; todo alvo é conferido (dentro da raiz, inexistente)
 * antes de gravar qualquer um; nunca sobrescreve nem apaga.
 */
class Scaffolder
{
    public const NAME = '/^[a-z][a-z0-9]*(-[a-z0-9]+)*(\.[a-z][a-z0-9]*(-[a-z0-9]+)*)?$/';

    public const GROUPS = ['Base', 'Overlay', 'Form', 'Navigation', 'Data', 'AI', 'Primitives'];

    private const SKILL_INDEX = 'skill/SKILL.md';

    private readonly string $root;

    public function __construct(?string $root = null)
    {
        $this->root = rtrim(str_replace('\\', '/', $root ?? dirname(__DIR__, 2)), '/');
    }

    /**
     * Só o checkout do kit tem tests/TestCase.php (export-ignore) e fica fora de vendor/.
     */
    public function isKitCheckout(): bool
    {
        if (str_contains($this->root, '/vendor/') || ! is_file($this->root.'/tests/TestCase.php') || ! is_file($this->root.'/composer.json')) {
            return false;
        }

        $composer = json_decode((string) file_get_contents($this->root.'/composer.json'), true);

        return is_array($composer) && ($composer['name'] ?? null) === 'devaction/ui';
    }

    public function root(): string
    {
        return $this->root;
    }

    /**
     * @param  'component'|'template'|'layout'  $kind
     * @param  array{group?: string, summary?: string, props?: list<array<string, mixed>>, children?: list<string>}  $input
     * @return list<array{path: string, contents: string, mode: 'create'|'update'}>
     */
    public function plan(string $kind, string $name, array $input = []): array
    {
        if (preg_match(self::NAME, $name) !== 1) {
            throw new InvalidArgumentException(sprintf('Invalid name "%s": use kebab-case, optional one dot (card, card.header).', $name));
        }

        $summary = $this->line((string) ($input['summary'] ?? ''), 200);

        if ($summary === '') {
            throw new InvalidArgumentException('Summary is required.');
        }

        $summary = $this->markdownSafe($summary);
        $props = $this->props($input['props'] ?? []);
        $children = $input['children'] ?? [];

        foreach ($children as $child) {
            if (preg_match(self::NAME, $child) !== 1 || str_contains($child, '.')) {
                throw new InvalidArgumentException(sprintf('Invalid child name "%s": use kebab-case without dots (header, body-row).', $child));
            }
        }

        $slug = str_replace('.', '-', $name);
        $studly = Str::studly($slug);
        $dir = str_replace('.', '/', $name);
        $views = 'resources/views/components/';
        $files = [];

        if ($kind === 'layout') {
            $files[$views.'layout/'.$dir.'.blade.php'] = $this->layoutBlade($name, $summary);
            $files['tests/Feature/'.$studly.'LayoutTest.php'] = $this->pest($studly.' layout', '<x-ui::layout.'.$name.'>Hello</x-ui::layout.'.$name.'>');
            $files['skill/examples/layouts/'.$slug.'.blade.php'] = $this->exampleBlade($name, 'layout.'.$name);

            $plan = $this->finish($files);
            $plan[] = ['path' => 'skill/references/layouts.md', 'contents' => $this->layoutsDoc($name, $summary), 'mode' => 'update'];

            return $plan;
        }

        $tag = $name;

        if ($kind === 'template') {
            $files[$views.$dir.'/index.blade.php'] = $this->blade($name, $summary, $props);
        } elseif ($children !== []) {
            $files[$views.$dir.'/index.blade.php'] = $this->blade($name, $summary, $props);

            foreach ($children as $child) {
                $files[$views.$dir.'/'.$child.'.blade.php'] = $this->blade($name.'.'.$child, 'Child of '.$name.'.', []);
            }
        } else {
            $files[$views.$dir.'.blade.php'] = $this->blade($name, $summary, $props);
        }

        $label = $kind === 'template' ? ' template' : ' component';
        $files['tests/Feature/'.$studly.($kind === 'template' ? 'Template' : 'Component').'Test.php'] = $this->pest($slug.$label, '<x-ui::'.$tag.'>Hello</x-ui::'.$tag.'>');

        $group = $kind === 'template' ? 'Templates' : (string) ($input['group'] ?? 'Base');

        if ($kind === 'component' && ! in_array($group, self::GROUPS, true)) {
            throw new InvalidArgumentException(sprintf('Invalid group "%s".', $group));
        }

        $files['skill/references/'.$slug.'.md'] = $this->reference($kind, $group, $name, $slug, $summary, $props, $children);
        $files['skill/examples/'.($kind === 'template' ? 'templates' : 'components').'/'.$slug.'.blade.php'] = $this->exampleBlade($name, $tag);

        $plan = $this->finish($files);
        $plan[] = ['path' => self::SKILL_INDEX, 'contents' => $this->indexed($group, $slug, $name, $summary), 'mode' => 'update'];

        return $plan;
    }

    /**
     * Grava o plano: tudo-ou-nada. Confere TODOS os alvos antes de criar qualquer arquivo; nunca sobrescreve.
     *
     * @param  list<array{path: string, contents: string, mode: 'create'|'update'}>  $plan
     * @return list<string> caminhos relativos criados/atualizados
     */
    public function write(array $plan): array
    {
        $realRoot = realpath($this->root);

        if ($realRoot === false) {
            throw new InvalidArgumentException('Package root does not exist.');
        }

        $targets = [];

        foreach ($plan as $file) {
            $relative = $file['path'];

            if ($relative === '' || str_starts_with($relative, '/') || str_contains($relative, '..') || str_contains($relative, "\0") || str_contains($relative, '\\')) {
                throw new InvalidArgumentException(sprintf('Unsafe path "%s".', $relative));
            }

            $absolute = $this->root.'/'.$relative;
            $ancestor = dirname($absolute);

            while (! file_exists($ancestor) && ! is_link($ancestor)) {
                $ancestor = dirname($ancestor);
            }

            $realAncestor = realpath($ancestor);

            if ($realAncestor === false || ($realAncestor !== $realRoot && ! str_starts_with($realAncestor, $realRoot.'/'))) {
                throw new InvalidArgumentException(sprintf('Path "%s" is outside the package root.', $relative));
            }

            if ($file['mode'] === 'create' && (file_exists($absolute) || is_link($absolute))) {
                throw new InvalidArgumentException(sprintf('File already exists: %s', $relative));
            }

            if ($file['mode'] === 'update' && (! is_file($absolute) || is_link($absolute))) {
                throw new InvalidArgumentException(sprintf('File to update not found: %s', $relative));
            }

            $targets[] = [$absolute, $file];
        }

        $written = [];

        foreach ($targets as [$absolute, $file]) {
            if ($file['mode'] === 'create') {
                if (! is_dir(dirname($absolute))) {
                    mkdir(dirname($absolute), 0755, true);
                }

                // "x" falha se o arquivo existir: sem sobrescrever nem em corrida.
                $handle = fopen($absolute, 'x');

                if ($handle === false) {
                    throw new InvalidArgumentException(sprintf('File already exists: %s', $file['path']));
                }

                fwrite($handle, $file['contents']);
                fclose($handle);
            } else {
                file_put_contents($absolute, $file['contents']);
            }

            $written[] = $file['path'];
        }

        return $written;
    }

    /**
     * Regenera manifesto e espelho para esta raiz (mesmos passos do ui:skill) sem tocar no container.
     *
     * @throws RuntimeException
     */
    public function sync(): void
    {
        try {
            $manifest = new ComponentManifest($this->root);
            $json = $manifest->json();
            $path = $manifest->path();
            $current = is_file($path) ? file_get_contents($path) : false;

            if ($current !== $json && file_put_contents($path, $json) === false) {
                throw new RuntimeException('Falha ao gravar '.$path);
            }

            ComponentManifest::flush();
            (new SkillMirror($this->root))->sync();
        } catch (Throwable $e) {
            throw new RuntimeException('Sync failed: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Neutraliza `#` inicial, crases e fences para o texto entrar em markdown sem virar estrutura.
     */
    private function markdownSafe(string $text): string
    {
        $text = str_replace(['|', '`'], ['\\|', '\\`'], $text);

        return (string) preg_replace_callback('/^#+/', fn (array $m): string => '\\'.$m[0], $text);
    }

    /**
     * @param  array<string, string>  $files
     * @return list<array{path: string, contents: string, mode: 'create'|'update'}>
     */
    private function finish(array $files): array
    {
        $plan = [];

        foreach ($files as $path => $contents) {
            $plan[] = ['path' => $path, 'contents' => $contents, 'mode' => 'create'];
        }

        return $plan;
    }

    private function line(string $text, int $max): string
    {
        $text = trim((string) preg_replace('/\s+/', ' ', $text));

        if (str_contains($text, '--}}') || str_contains($text, '{{--') || str_contains($text, '<?')) {
            throw new InvalidArgumentException('Text contains forbidden sequences.');
        }

        return mb_substr($text, 0, $max);
    }

    /**
     * @param  list<array<string, mixed>>  $props
     * @return list<array{name: string, type: string, default: string|null, description: string}>
     */
    private function props(array $props): array
    {
        $clean = [];

        foreach ($props as $prop) {
            $name = $prop['name'] ?? null;
            $type = $prop['type'] ?? 'string';

            if (! is_string($name) || preg_match('/^[a-z][A-Za-z0-9]*$/', $name) !== 1 || ! is_string($type) || preg_match('/^[a-z|]{1,30}$/', $type) !== 1) {
                throw new InvalidArgumentException('Invalid prop definition.');
            }

            $default = $prop['default'] ?? null;
            $clean[] = [
                'name' => $name,
                'type' => $type,
                'default' => is_scalar($default) ? $this->line((string) $default, 60) : null,
                'description' => $this->line(is_string($prop['description'] ?? null) ? $prop['description'] : '', 120),
            ];
        }

        return $clean;
    }

    /**
     * @param  list<array{name: string, type: string, default: string|null, description: string}>  $props
     */
    private function blade(string $name, string $summary, array $props): string
    {
        $header = "{{--\n    ".$summary."\n\n    <x-ui::".$name.'>…</x-ui::'.$name.">\n";

        if ($props !== []) {
            $header .= "\n";

            foreach ($props as $prop) {
                $header .= '    '.$prop['name'].': '.$prop['type'].($prop['description'] !== '' ? ' — '.$prop['description'] : '')."\n";
            }
        }

        $header .= "--}}\n";
        $entries = '';

        foreach ($props as $prop) {
            $entries .= '    '.var_export($prop['name'], true).' => '.($prop['default'] === null ? 'null' : var_export($prop['default'], true)).",\n";
        }

        $propsBlock = $entries === '' ? "@props([])\n" : "@props([\n".$entries."])\n";

        return $header.$propsBlock."\n".'<div data-'.str_replace('.', '-', $name).' {{ $attributes->class([]) }}>{{ $slot }}</div>'."\n";
    }

    private function layoutBlade(string $name, string $summary): string
    {
        return "{{--\n    ".$summary."\n\n    <x-ui::layout.".$name.'>…</x-ui::layout.'.$name.">\n--}}\n@props([])\n\n<div data-layout=\"".str_replace('.', '-', $name)."\" {{ \$attributes->class([]) }}>{{ \$slot }}</div>\n";
    }

    private function pest(string $label, string $markup): string
    {
        return "<?php\n\nuse Illuminate\\Support\\Facades\\Blade;\n\ntest('".$label." renders its slot', function () {\n    expect(Blade::render('".$markup."'))->toContain('Hello');\n});\n";
    }

    private function exampleBlade(string $name, string $tag): string
    {
        return '{{-- '.Str::headline($name).': minimal example — see references/'.str_replace('.', '-', $name).".md --}}\n<x-ui::".$tag.'>Hello</x-ui::'.$tag.">\n";
    }

    /**
     * @param  list<array{name: string, type: string, default: string|null, description: string}>  $props
     * @param  list<string>  $children
     */
    private function reference(string $kind, string $group, string $name, string $slug, string $summary, array $props, array $children): string
    {
        $components = [$name];

        foreach ($children as $child) {
            $components[] = $name.'.'.$child;
        }

        $rows = '';

        foreach ($props as $prop) {
            $rows .= '| `'.$prop['name'].'` | '.$prop['type'].' | `'.($prop['default'] ?? 'null').'` | '.str_replace('|', '\\|', $prop['description'] !== '' ? $prop['description'] : 'TODO').' |'."\n";
        }

        $rows = $rows === '' ? "| TODO | | | |\n" : $rows;
        $tag = $name;

        return "---\nkind: ".$kind."\ngroup: ".$group."\ncomponents: [".implode(', ', $components)."]\n---\n# ".Str::headline($name).' · `<x-ui::'.$tag.">`\n\n".$summary."\n\n## Examples\n\n### Basic\n\nTODO: describe the example.\n\n```blade\n<x-ui::".$tag.'>Hello</x-ui::'.$tag.">\n```\n\n## API\n\n### `x-ui::".$tag."`\n\n| Prop | Type | Default | Description |\n| --- | --- | --- | --- |\n".$rows."\n## Accessibility\n\n- TODO: roles, keyboard and labels.\n";
    }

    /**
     * SKILL.md com a linha nova sob `### {group}`, em ordem alfabética; sem duplicar.
     */
    private function indexed(string $group, string $slug, string $name, string $summary): string
    {
        $path = $this->root.'/'.self::SKILL_INDEX;
        $content = is_file($path) ? (string) file_get_contents($path) : '';
        $lines = explode("\n", $content);
        $entry = '- ['.Str::headline($name).'](references/'.$slug.'.md) — '.$summary;

        foreach ($lines as $line) {
            if (str_contains($line, '](references/'.$slug.'.md)')) {
                return $content;
            }
        }

        $start = array_search('### '.$group, $lines, true);

        if ($start === false) {
            throw new InvalidArgumentException(sprintf('Group heading "### %s" not found in %s.', $group, self::SKILL_INDEX));
        }

        $insertAt = null;
        $last = $start;

        for ($i = $start + 1; $i < count($lines) && ! str_starts_with($lines[$i], '#'); $i++) {
            if (! str_starts_with($lines[$i], '- [')) {
                continue;
            }

            $last = $i;

            if ($insertAt === null && preg_match('/^- \[([^\]]*)\]/', $lines[$i], $match) === 1 && strcasecmp($match[1], Str::headline($name)) > 0) {
                $insertAt = $i;
            }
        }

        array_splice($lines, $insertAt ?? ($last === $start ? $start + 2 : $last + 1), 0, [$entry]);

        return implode("\n", $lines);
    }

    /**
     * layouts.md com `layout.{name}` na lista `components:` (ordem alfabética entre os layout.*) e uma seção TODO antes de "Layout pieces".
     */
    private function layoutsDoc(string $name, string $summary): string
    {
        $path = $this->root.'/skill/references/layouts.md';

        if (! is_file($path) || is_link($path)) {
            throw new InvalidArgumentException('skill/references/layouts.md not found.');
        }

        $content = (string) file_get_contents($path);
        $claim = 'layout.'.$name;

        if (preg_match('/^components: \[(.*)\]$/m', $content, $match) !== 1) {
            throw new InvalidArgumentException('layouts.md has no inline components list.');
        }

        $items = array_map('trim', explode(',', $match[1]));

        if (in_array($claim, $items, true)) {
            throw new InvalidArgumentException('Layout already claimed in layouts.md: '.$claim);
        }

        $at = null;
        $last = -1;

        foreach ($items as $i => $item) {
            if (str_starts_with($item, 'layout.')) {
                $last = $i;

                if ($at === null && strcmp($item, $claim) > 0) {
                    $at = $i;
                }
            }
        }

        array_splice($items, $at ?? $last + 1, 0, [$claim]);
        $content = (string) preg_replace_callback('/^components: \[.*\]$/m', fn (): string => 'components: ['.implode(', ', $items).']', $content, 1);

        $section = '## '.Str::headline($name)."\n\n".$summary."\n\nTODO: example in `skill/examples/layouts/".str_replace('.', '-', $name).".blade.php`.\n\n";

        return str_contains($content, "## Layout pieces\n")
            ? str_replace("## Layout pieces\n", $section."## Layout pieces\n", $content)
            : rtrim($content)."\n\n".rtrim($section)."\n";
    }
}
