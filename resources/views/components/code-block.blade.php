{{--
    Bloco de código com cabeçalho, copiar, números e destaque de linhas.

    <x-ui::code-block filename="resources/css/app.css" language="css">
    @import "tailwindcss";
    @import "../../vendor/devaction/ui/resources/css/devaction-ui.css";
    </x-ui::code-block>

    <x-ui::code-block language="blade" lines highlight="2">
    @verbatim
    <x-ui::button variant="primary">Salvar</x-ui::button>
    @endverbatim
    </x-ui::code-block>

    <x-ui::code-block language="php" :code="$snippet" />

    Para código Blade no slot, use @verbatim; ou passe o texto pela prop code.
    language:  blade · html · heex · php · js · css · bash · json · text
    highlight: "2" ou "2,4-6" — linhas marcadas com a faixa âmbar da IA
--}}
@props([
    'code' => null,
    'filename' => null,
    'language' => null,
    'copyable' => true,
    'lines' => false,
    'highlight' => null,
    'wrap' => false,
    'flush' => false,
])

@php
    $source = \DevAction\Ui\Support\CodeHighlighter::dedent(
        $code ?? html_entity_decode((string) $slot, ENT_QUOTES | ENT_HTML5),
    );

    $highlightedLines = collect(preg_split('/\s*,\s*/', trim((string) $highlight)))
        ->filter()
        ->flatMap(function (string $range): array {
            [$start, $end] = array_pad(array_map('intval', explode('-', $range, 2)), 2, null);

            return range($start, $end ?? $start);
        })
        ->all();

    $codeLines = (new \DevAction\Ui\Support\CodeHighlighter)->lines($source, $language);
    $hasHeader = $filename || $language;
    $languageLabel = $language ? strtoupper($language) : null;
@endphp

<div
    data-code-block
    @if ($copyable) x-data="{ copied: false }" @endif
    {{ $attributes->class([
        'group/code relative overflow-hidden bg-code text-left text-code-ink',
        'rounded-2xl' => ! $flush,
    ]) }}
>
    @if ($hasHeader)
        <div class="flex items-center gap-3 border-b border-code-line py-2 pr-2 pl-3.5 font-mono text-xs">
            @if ($filename)
                <span class="truncate text-code-muted">{{ $filename }}</span>
            @endif
            <span class="ml-auto flex items-center gap-2">
                @if ($languageLabel)
                    <span class="text-[11px] tracking-[.06em] text-code-muted">{{ $languageLabel }}</span>
                @endif
                @if ($copyable)
                    <button
                        type="button"
                        x-on:click="navigator.clipboard?.writeText(@js($source)); copied = true; setTimeout(() => copied = false, 1500)"
                        x-bind:aria-label="copied ? 'Código copiado' : 'Copiar código'"
                        aria-label="Copiar código"
                        class="inline-flex h-7 cursor-pointer items-center gap-1.5 rounded-lg border border-[#3f3f46] bg-[#27272a] px-2.5 font-sans text-xs text-code-ink transition hover:bg-[#3f3f46]"
                    >
                        <x-ui::icon name="copy-01" class="size-3.5" x-show="! copied" />
                        <x-ui::icon name="tick-02" class="size-3.5 text-code-tag" x-show="copied" x-cloak />
                        <span x-text="copied ? 'Copiado' : 'Copiar'">Copiar</span>
                    </button>
                @endif
            </span>
        </div>
    @elseif ($copyable)
        <button
            type="button"
            x-on:click="navigator.clipboard?.writeText(@js($source)); copied = true; setTimeout(() => copied = false, 1500)"
            x-bind:aria-label="copied ? 'Código copiado' : 'Copiar código'"
            aria-label="Copiar código"
            class="absolute top-2.5 right-2.5 inline-flex size-7 cursor-pointer items-center justify-center rounded-lg border border-[#3f3f46] bg-[#27272a] text-code-ink opacity-0 transition group-hover/code:opacity-100 hover:bg-[#3f3f46] focus-visible:opacity-100"
        >
            <x-ui::icon name="copy-01" class="size-3.5" x-show="! copied" />
            <x-ui::icon name="tick-02" class="size-3.5 text-code-tag" x-show="copied" x-cloak />
        </button>
    @endif

    <pre @class([
        'overflow-x-auto py-3.5 font-mono text-[13px] leading-[1.7]',
        'whitespace-pre-wrap break-words' => $wrap,
        'whitespace-pre' => ! $wrap,
    ])><code class="block min-w-max">@foreach ($codeLines as $lineNumber => $lineHtml)<span @class([
        'block pr-4',
        'pl-4' => ! $lines,
        'bg-amber/10 shadow-[inset_3px_0_0_var(--color-amber)]' => in_array($lineNumber + 1, $highlightedLines, true),
    ]) data-line="{{ $lineNumber + 1 }}">@if ($lines)<span class="mr-4 inline-block w-10 pr-1 text-right text-code-comment select-none" aria-hidden="true">{{ $lineNumber + 1 }}</span>@endif{!! $lineHtml === '' ? ' ' : $lineHtml !!}</span>@endforeach</code></pre>
</div>
