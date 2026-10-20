<?php

namespace DevAction\Ui\Support;

/**
 * Destaque de sintaxe leve, sem dependências, para o <x-ui::code-block>.
 *
 * Trabalha linha a linha e cobre o que a documentação do kit usa: marcação (Blade, HTML, HEEx),
 * PHP, JavaScript, CSS, bash e JSON. Cada token vira um <span> com uma classe de cor do tema.
 */
class CodeHighlighter
{
    protected const MARKUP_LANGUAGES = ['blade', 'html', 'heex', 'xml', 'vue', 'svg'];

    /**
     * @var array<string, list<string>>
     */
    protected const KEYWORDS = [
        'php' => ['use', 'namespace', 'class', 'function', 'fn', 'return', 'public', 'protected', 'private', 'static', 'new', 'if', 'else', 'foreach', 'as', 'match', 'null', 'true', 'false', 'array', 'string', 'int', 'bool', 'void', 'extends', 'implements'],
        'js' => ['import', 'from', 'export', 'default', 'const', 'let', 'var', 'function', 'return', 'new', 'if', 'else', 'for', 'of', 'in', 'async', 'await', 'true', 'false', 'null', 'undefined', 'class', 'this'],
        'css' => ['@import', '@source', '@theme', '@utility', '@layer', '@apply', '@custom-variant', '@media', '@keyframes'],
        'bash' => ['composer', 'php', 'artisan', 'npm', 'npx', 'git', 'cd', 'export', 'sudo'],
    ];

    /**
     * @var array<string, string>
     */
    protected const CLASSES = [
        'comment' => 'text-code-comment',
        'string' => 'text-code-string',
        'tag' => 'text-code-tag',
        'attribute' => 'text-code-attribute',
        'keyword' => 'text-code-keyword',
        'echo' => 'text-code-accent',
        'variable' => 'text-code-attribute',
        'number' => 'text-code-accent',
    ];

    /**
     * Destaca cada linha e devolve a lista de linhas já escapadas em HTML.
     *
     * @return list<string>
     */
    public function lines(string $code, ?string $language): array
    {
        $language = $this->normalizeLanguage($language);

        return array_map(
            fn (string $line): string => $this->highlightLine($line, $language),
            explode("\n", $code),
        );
    }

    /**
     * Remove a indentação comum e as linhas vazias das pontas, para código vindo de heredoc ou slot.
     */
    public static function dedent(string $code): string
    {
        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $code));

        while ($lines !== [] && trim($lines[0]) === '') {
            array_shift($lines);
        }

        while ($lines !== [] && trim(end($lines)) === '') {
            array_pop($lines);
        }

        $indents = array_map(
            fn (string $line): int => strlen($line) - strlen(ltrim($line, " \t")),
            array_filter($lines, fn (string $line): bool => trim($line) !== ''),
        );

        $commonIndent = $indents === [] ? 0 : min($indents);

        return implode("\n", array_map(fn (string $line): string => substr($line, $commonIndent), $lines));
    }

    protected function normalizeLanguage(?string $language): string
    {
        return match (strtolower((string) $language)) {
            'js', 'javascript', 'ts', 'typescript', 'mjs' => 'js',
            'sh', 'bash', 'shell', 'zsh', 'terminal' => 'bash',
            'blade', 'html', 'heex', 'xml', 'vue', 'svg' => strtolower((string) $language),
            'php', 'css', 'json' => strtolower((string) $language),
            default => 'text',
        };
    }

    protected function highlightLine(string $line, string $language): string
    {
        if ($language === 'text') {
            return e($line);
        }

        $pattern = $this->patternFor($language);

        $highlighted = '';
        $offset = 0;

        preg_match_all($pattern, $line, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            [$token, $position] = $match[0];
            $type = $this->tokenType($match);

            $highlighted .= e(substr($line, $offset, $position - $offset));
            $highlighted .= $type
                ? '<span class="'.self::CLASSES[$type].'">'.e($token).'</span>'
                : e($token);

            $offset = $position + strlen($token);
        }

        return $highlighted.e(substr($line, $offset));
    }

    /**
     * @param  array<int|string, array{0: string, 1: int}>  $match
     */
    protected function tokenType(array $match): ?string
    {
        foreach (array_keys(self::CLASSES) as $type) {
            if (isset($match[$type]) && $match[$type][1] !== -1 && $match[$type][0] !== '') {
                return $type;
            }
        }

        return null;
    }

    protected function patternFor(string $language): string
    {
        $string = '(?<string>"(?:[^"\\\\]|\\\\.)*"|\'(?:[^\'\\\\]|\\\\.)*\')';

        if (in_array($language, self::MARKUP_LANGUAGES, true)) {
            return '/(?<comment>\{\{--.*?--\}\}|<!--.*?-->)'
                .'|(?<echo>\{\{.*?\}\}|\{!!.*?!!\}|@[a-zA-Z]+)'
                .'|(?<tag><\/?[a-zA-Z][\w:.\-]*|\/?>)'
                .'|(?<attribute>(?<=\s)[:@a-zA-Z][\w:.\-]*(?==))'
                .'|'.$string.'/';
        }

        $keywords = implode('|', array_map(fn (string $keyword): string => preg_quote($keyword, '/'), self::KEYWORDS[$language] ?? []));
        $keywordPattern = $keywords === '' ? '' : '|(?<keyword>(?<![\w@$-])(?:'.$keywords.')(?![\w-]))';

        $comment = match ($language) {
            'bash' => '(?<comment>(?:^|(?<=\s))#.*$)',
            'css' => '(?<comment>\/\*.*?\*\/|\/\*.*$)',
            'json' => '(?<comment>(?!))',
            default => '(?<comment>\/\/.*$|#(?!\[).*$|\/\*.*?\*\/|\/\*.*$)',
        };

        return '/'.$comment
            .'|'.$string
            .'|(?<variable>\$[a-zA-Z_][\w]*|--[a-zA-Z][\w-]*)'
            .$keywordPattern
            .'|(?<number>(?<![\w#-])\d+(?:\.\d+)?(?:px|rem|em|ms|s|%)?(?![\w]))/';
    }
}
