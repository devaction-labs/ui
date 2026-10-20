<?php

use DevAction\Ui\Support\CodeHighlighter;
use Illuminate\Support\Facades\Blade;

test('code block renders a header with filename, language and copy button', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::code-block filename="app.css" language="css">
            @import "tailwindcss";
        </x-ui::code-block>
        BLADE);

    expect($html)
        ->toContain('data-code-block')
        ->toContain('app.css')
        ->toContain('CSS')
        ->toContain('aria-label="Copiar código"')
        ->toContain('<span class="text-code-keyword">@import</span>')
        ->toContain('<span class="text-code-string">&quot;tailwindcss&quot;</span>');
});

test('code block keeps blade source from a verbatim slot escaped and highlighted', function () {
    $html = Blade::render(<<<'BLADE'
        <x-ui::code-block language="blade">
            @verbatim
            <x-ui::button variant="primary">{{ $label }}</x-ui::button>
            @endverbatim
        </x-ui::code-block>
        BLADE);

    expect($html)
        ->toContain('<span class="text-code-tag">&lt;x-ui::button</span>')
        ->toContain('<span class="text-code-attribute">variant</span>')
        ->toContain('<span class="text-code-accent">{{ $label }}</span>')
        ->not->toContain('<button type="button" class="group/button');
});

test('code block accepts code through the prop and dedents it', function () {
    $html = Blade::render('<x-ui::code-block :code="$code" language="text" :copyable="false" />', [
        'code' => "\n        primeira\n          segunda\n",
    ]);

    expect($html)
        ->toContain('data-line="1">primeira</span>')
        ->toContain('data-line="2">  segunda</span>')
        ->not->toContain('Copiar');
});

test('code block shows line numbers and highlights ranges', function () {
    $html = Blade::render('<x-ui::code-block :code="$code" lines highlight="2-3" />', [
        'code' => "um\ndois\ntrês\nquatro",
    ]);

    expect($html)->toContain('select-none" aria-hidden="true">4</span>');
    expect(substr_count($html, 'shadow-[inset_3px_0_0_var(--color-amber)]'))->toBe(2);
});

test('code block without header shows a floating copy button', function () {
    $html = Blade::render('<x-ui::code-block code="composer require devaction/ui" />');

    expect($html)->toContain('absolute top-2.5 right-2.5')->toContain('rounded-2xl');
});

test('flush code block drops the rounded corners', function () {
    expect(Blade::render('<x-ui::code-block code="x" flush />'))->not->toContain('rounded-2xl');
});

test('highlighter colors comments, strings, keywords and variables per language', function (string $language, string $code, string $expected) {
    expect((new CodeHighlighter)->lines($code, $language)[0])->toContain($expected);
})->with([
    ['bash', 'composer require devaction/ui # instala', '<span class="text-code-comment"># instala</span>'],
    ['js', "const theme = 'dark';", '<span class="text-code-keyword">const</span>'],
    ['php', 'return $this->name;', '<span class="text-code-attribute">$this</span>'],
    ['css', 'gap: 12px; --frame: #fff;', '<span class="text-code-attribute">--frame</span>'],
    ['blade', '{{-- comentário --}}', '<span class="text-code-comment">{{-- comentário --}}</span>'],
]);

test('highlighter escapes html in plain text', function () {
    expect((new CodeHighlighter)->lines('<script>alert(1)</script>', 'text')[0])
        ->toBe('&lt;script&gt;alert(1)&lt;/script&gt;');
});
