<?php

namespace DevAction\Ui\Support;

use Illuminate\Support\Str;

/**
 * Lê um componente Blade como texto (sem executar nada) e extrai nome, doc, props, attributes, slots e filhos.
 */
class BladeComponentParser
{
    /**
     * @return array{name: string, tag: string, file: string, doc: string, props: list<array{name: string, attribute: string, default: mixed}>, attributes: list<string>, slots: list<string>, children: list<string>}
     */
    public function parse(string $path, string $componentsRoot): array
    {
        $root = rtrim($componentsRoot, '/');
        $source = (string) file_get_contents($path);
        $file = ltrim(substr($path, strlen($root)), '/');
        $name = $this->name($file);
        $doc = $this->doc($source);
        $props = $this->props($source);

        return [
            'name' => $name,
            'tag' => 'x-ui::'.$name,
            'file' => $file,
            'doc' => $doc,
            'props' => $props,
            'attributes' => $this->attributes($source),
            'slots' => $this->slots($source, $doc, array_column($props, 'name')),
            'children' => $this->children($path, $root),
        ];
    }

    /**
     * Nome do componente a partir do caminho relativo: "/" vira ".", e o sufixo "index" some.
     */
    private function name(string $file): string
    {
        $name = str_replace('/', '.', substr($file, 0, -strlen('.blade.php')));

        return $name === 'index' ? $name : (string) preg_replace('/\.index$/', '', $name);
    }

    /**
     * Texto do primeiro comentário Blade, sem a indentação comum.
     */
    private function doc(string $source): string
    {
        if (! preg_match('/\{\{--(.*?)--\}\}/s', $source, $match)) {
            return '';
        }

        $lines = explode("\n", str_replace("\r\n", "\n", $match[1]));
        $indent = null;

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }

            $size = strlen($line) - strlen(ltrim($line));
            $indent = $indent === null ? $size : min($indent, $size);
        }

        $lines = array_map(fn (string $line): string => rtrim(substr($line, min((int) $indent, strlen($line) - strlen(ltrim($line))))), $lines);

        return trim(implode("\n", $lines));
    }

    /**
     * Pares nome/default do @props([...]), lidos com o tokenizer do PHP.
     *
     * @return list<array{name: string, attribute: string, default: mixed}>
     */
    private function props(string $source): array
    {
        $array = $this->propsArray($source);

        if ($array === null) {
            return [];
        }

        $tokens = token_get_all('<?php '.$array.';');
        $props = [];
        $depth = 0;
        $key = null;
        $default = '';
        $inValue = false;

        foreach ($tokens as $token) {
            $text = is_array($token) ? $token[1] : $token;

            if (is_array($token) && in_array($token[0], [T_OPEN_TAG, T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                if ($inValue && $token[0] === T_WHITESPACE) {
                    $default .= ' ';
                }

                continue;
            }

            if ($text === '[' || $text === '(') {
                $depth++;

                if ($depth === 1) {
                    continue;
                }
            } elseif ($text === ']' || $text === ')') {
                $depth--;

                if ($depth === 0) {
                    if ($key !== null) {
                        $props[] = $this->prop($key, $default);
                    }

                    [$key, $default, $inValue] = [null, '', false];

                    break;
                }
            }

            if ($depth === 1 && ! $inValue && is_array($token) && $token[0] === T_CONSTANT_ENCAPSED_STRING) {
                $key = $key ?? stripslashes(substr($text, 1, -1));

                continue;
            }

            if ($depth === 1 && $text === '=>') {
                $inValue = true;

                continue;
            }

            if ($depth === 1 && $text === ',') {
                if ($key !== null) {
                    $props[] = $this->prop($key, $default);
                }

                [$key, $default, $inValue] = [null, '', false];

                continue;
            }

            if ($inValue) {
                $default .= $text;
            }
        }

        return $props;
    }

    /**
     * Monta a prop com o nome em kebab e o default já convertido.
     *
     * @return array{name: string, attribute: string, default: mixed}
     */
    private function prop(string $key, string $default): array
    {
        return ['name' => $key, 'attribute' => Str::kebab($key), 'default' => $this->literal(trim($default))];
    }

    /**
     * Texto do array dentro de @props(...), com os parênteses balanceados.
     */
    private function propsArray(string $source): ?string
    {
        $start = strpos($source, '@props(');

        if ($start === false) {
            return null;
        }

        $begin = $start + strlen('@props(');
        $depth = 1;
        $quote = null;

        for ($i = $begin, $length = strlen($source); $i < $length; $i++) {
            $char = $source[$i];

            if ($quote !== null) {
                if ($char === '\\') {
                    $i++;
                } elseif ($char === $quote) {
                    $quote = null;
                }

                continue;
            }

            if ($char === "'" || $char === '"') {
                $quote = $char;
            } elseif ($char === '(') {
                $depth++;
            } elseif ($char === ')' && --$depth === 0) {
                return substr($source, $begin, $i - $begin);
            }
        }

        return null;
    }

    /**
     * Valor literal vira valor PHP; qualquer outra expressão fica como texto cru.
     */
    private function literal(string $expression): mixed
    {
        $lower = strtolower($expression);

        return match (true) {
            $lower === 'null' => null,
            $lower === 'true' => true,
            $lower === 'false' => false,
            (bool) preg_match('/^-?\d+$/', $expression) => (int) $expression,
            (bool) preg_match('/^-?\d*\.\d+$/', $expression) => (float) $expression,
            (bool) preg_match("/^'((?:[^'\\\\]|\\\\.)*)'$/s", $expression, $m) => str_replace(["\\'", '\\\\'], ["'", '\\'], $m[1]),
            (bool) preg_match('/^"((?:[^"\\\\$]|\\\\.)*)"$/s', $expression, $m) => stripcslashes($m[1]),
            default => (string) preg_replace('/\s+/', ' ', $expression),
        };
    }

    /**
     * Chaves lidas com $attributes->get() e ->has(), sem repetição.
     *
     * @return list<string>
     */
    private function attributes(string $source): array
    {
        preg_match_all('/\$attributes->(?:get|has)\(\'([^\']+)\'/', $source, $matches);

        return array_values(array_unique($matches[1]));
    }

    /**
     * Slots citados na doc, ou usados com isset()/isNotEmpty(), menos props e variáveis reservadas.
     *
     * @param  list<string>  $props
     * @return list<string>
     */
    private function slots(string $source, string $doc, array $props): array
    {
        preg_match_all('/<x-slot:([a-z-]+)/', $doc, $fromDoc);
        preg_match_all('/isset\(\$([a-zA-Z]+)\)/', $source, $fromIsset);
        preg_match_all('/\$([a-zA-Z]+)->isNotEmpty\(\)/', $source, $fromEmpty);

        $slots = array_merge($fromDoc[1], $fromIsset[1], $fromEmpty[1]);

        return array_values(array_unique(array_diff($slots, $props, ['slot', 'attributes'])));
    }

    /**
     * Para um arquivo index, os componentes irmãos na mesma pasta (arquivos e subpastas com index).
     *
     * @return list<string>
     */
    private function children(string $path, string $root): array
    {
        if (basename($path) !== 'index.blade.php') {
            return [];
        }

        $directory = dirname($path);
        $children = [];

        foreach (scandir($directory) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..' || $entry === 'index.blade.php') {
                continue;
            }

            $full = $directory.'/'.$entry;

            if (is_dir($full) && is_file($full.'/index.blade.php')) {
                $children[] = $this->name(ltrim(substr($full.'/index.blade.php', strlen($root)), '/'));
            } elseif (is_file($full) && str_ends_with($entry, '.blade.php')) {
                $children[] = $this->name(ltrim(substr($full, strlen($root)), '/'));
            }
        }

        sort($children);

        return $children;
    }
}
