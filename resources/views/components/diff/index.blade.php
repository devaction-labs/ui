{{--
    Diff de um arquivo com comentários presos às linhas, como no review de PR.

    <x-ui::diff path="app/Models/Milestone.php" :patch="$file->patch" comment="addComment">
        <x-ui::diff.thread line="18" resolve="resolveThread(7)">
            <x-ui::diff.comment author="Revisor DevAction" ai when="agora">due_on virou obrigatório…</x-ui::diff.comment>
            <x-ui::diff.comment author="Alex" when="há 2 min" suggestion-from="    'due_on' => ['required']," suggestion-to="    'due_on' => ['nullable'],">Pode sugerir?</x-ui::diff.comment>
        </x-ui::diff.thread>
    </x-ui::diff>

    patch:   diff unificado (o patch do GitHub, com @@ -a,b +c,d @@)
    lines:   ou as linhas prontas: [['type' => 'add|del|ctx', 'old' => 15, 'new' => null, 'code' => '…']]
    comment: método do Livewire chamado com (linha, texto) no "Comentar"; sempre dispara diff-comment com { line, body }
    commentable: false esconde o + das linhas
--}}
@props([
    'path' => null,
    'patch' => null,
    'lines' => [],
    'comment' => null,
    'commentable' => true,
    'seen' => null,
])

@php
    $rows = $lines;

    if ($patch !== null) {
        $rows = [];
        $oldLine = 0;
        $newLine = 0;

        foreach (preg_split('/\R/', rtrim((string) $patch, "\n")) as $patchLine) {
            if (preg_match('/^@@ -(\d+)(?:,\d+)? \+(\d+)(?:,\d+)? @@(.*)$/', $patchLine, $hunk)) {
                $oldLine = (int) $hunk[1];
                $newLine = (int) $hunk[2];
                $rows[] = ['type' => 'hunk', 'old' => null, 'new' => null, 'code' => trim($patchLine)];

                continue;
            }

            $sign = substr($patchLine, 0, 1);
            $code = substr($patchLine, 1);

            if ($sign === '+') {
                $rows[] = ['type' => 'add', 'old' => null, 'new' => $newLine++, 'code' => $code];
            } elseif ($sign === '-') {
                $rows[] = ['type' => 'del', 'old' => $oldLine++, 'new' => null, 'code' => $code];
            } elseif ($sign !== '\\') {
                $rows[] = ['type' => 'ctx', 'old' => $oldLine++, 'new' => $newLine++, 'code' => $code];
            }
        }
    }

    $additions = collect($rows)->where('type', 'add')->count();
    $deletions = collect($rows)->where('type', 'del')->count();
    $diffId = 'ui-diff-'.substr(md5((string) $path.count($rows)), 0, 8);

    // Cada linha ocupa a ordem 2n e o que vem embaixo dela (comentário novo ou conversa) a 2n+1
    $orderVariables = collect($rows)
        ->map(fn (array $row, int $index): ?string => in_array($row['type'], ['add', 'ctx'], true) ? '--diff-line-'.($row['new'] ?? $row['old']).': '.($index * 2 + 1) : null)
        ->filter()
        ->implode('; ');
@endphp

<section
    data-diff
    id="{{ $diffId }}"
    aria-label="{{ __('Diff of :file', ['file' => $path ?? __('file')]) }}"
    x-data="uiDiff({ comment: @js($comment) })"
    {{ $attributes->class(['flex min-w-0 flex-col overflow-hidden rounded-2xl border border-line bg-card text-ink']) }}
>
    @if ($path)
        <div class="flex h-[52px] items-center gap-2.5 border-b border-line-soft px-4">
            <x-ui::icon name="file-01" class="size-4 text-muted" />
            <span class="min-w-0 truncate font-mono text-[13px]">{{ $path }}</span>
            <span class="shrink-0 font-mono text-[11px]"><span class="text-ok-fg">+{{ $additions }}</span> <span class="text-risk-fg">−{{ $deletions }}</span></span>
            @if ($seen !== null)
                <label class="ml-auto flex shrink-0 cursor-pointer items-center gap-1.5 text-[13px] text-ink-3"><x-ui::checkbox :checked="(bool) $seen" />{{ __('Viewed') }}</label>
            @endif
        </div>
    @endif

    <div class="overflow-x-auto font-mono text-[12.5px]">
        <div class="flex min-w-max flex-col" style="{{ $orderVariables }}">
            @foreach ($rows as $rowIndex => $row)
                @php
                    $lineKey = $row['new'] ?? $row['old'];
                    $canComment = $commentable && in_array($row['type'], ['add', 'ctx'], true);
                @endphp
                @if ($row['type'] === 'hunk')
                    <div class="bg-info-bg px-4 py-1.5 text-info-fg" style="order: {{ $rowIndex * 2 }}">{{ $row['code'] }}</div>
                    @continue
                @endif
                <div
                    data-diff-line="{{ $lineKey }}"
                    style="order: {{ $rowIndex * 2 }}"
                    @class([
                        'group grid min-h-[26px] grid-cols-[44px_44px_22px_minmax(0,1fr)] items-center',
                        'bg-ok-bg text-ok-fg' => $row['type'] === 'add',
                        'bg-risk-bg text-risk-fg' => $row['type'] === 'del',
                        'text-ink-2' => $row['type'] === 'ctx',
                    ])
                >
                    <span class="pr-2 text-right text-muted select-none">{{ $row['old'] }}</span>
                    <span class="pr-2 text-right text-muted select-none">{{ $row['new'] }}</span>
                    <span class="flex items-center justify-center">
                        @if ($canComment)
                            <button
                                type="button"
                                x-on:click="toggleDraft({{ (int) $lineKey }})"
                                aria-label="{{ __('Comment on line :line', ['line' => $lineKey]) }}"
                                x-bind:class="hasThread({{ (int) $lineKey }}) || draftLine === {{ (int) $lineKey }} ? 'bg-ai text-white opacity-100' : 'bg-line text-ink-3 opacity-0 group-hover:opacity-100 focus-visible:opacity-100'"
                                class="flex size-[18px] cursor-pointer items-center justify-center rounded-[5px] text-[13px] leading-none transition"
                            >+</button>
                        @endif
                    </span>
                    <span class="py-1.5 whitespace-pre">{{ $row['type'] === 'add' ? '+' : ($row['type'] === 'del' ? '−' : ' ') }} {{ $row['code'] }}</span>
                </div>
                @if ($canComment)
                    <div x-show="draftLine === {{ (int) $lineKey }}" x-cloak style="order: {{ $rowIndex * 2 + 1 }}" class="border-y border-line-soft bg-subtle py-2.5 pr-4 pl-[110px] font-sans text-sm">
                        <div class="max-w-[680px] rounded-xl border border-line bg-card p-3">
                            <x-ui::diff.composer :line="(int) $lineKey" />
                        </div>
                    </div>
                @endif
            @endforeach

            {{ $slot }}
        </div>
    </div>
</section>
