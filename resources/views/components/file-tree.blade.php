{{--
    Árvore de arquivos de um PR: pastas montadas a partir dos caminhos, status, +/− e achados por arquivo.

    <x-ui::file-tree wire:model.live="file" :files="[
        ['path' => 'app/Models/Milestone.php', 'status' => 'M', 'additions' => 18, 'deletions' => 6, 'findings' => 2, 'level' => 'risk'],
        ['path' => 'database/migrations/2026_10_14_add_due_on.php', 'status' => 'A', 'additions' => 12],
        ['path' => 'app/Http/Controllers/LegacyReportController.php', 'status' => 'D', 'deletions' => 48],
    ]" />

    status: A (novo) · M (alterado) · D (removido) · findings + level (risk · warn) mostram o selo de achados
    wire:model / x-model: caminho do arquivo escolhido; também dispara select-file com { path }
    filters: false esconde a busca e as abas Todos · Com achados · Novos
--}}
@props([
    'files' => [],
    'heading' => null,
    'value' => null,
    'filters' => true,
])

@php
    $heading ??= __('Changed files');
    $nodes = [];
    $directories = [];

    foreach ($files as $file) {
        $parts = explode('/', trim($file['path'], '/'));

        for ($index = 1; $index < count($parts); $index++) {
            $directories[implode('/', array_slice($parts, 0, $index))] = true;
        }
    }

    $sorted = collect(array_keys($directories))
        ->map(fn (string $path): array => ['path' => $path, 'directory' => true])
        ->merge(collect($files)->map(fn (array $file): array => [...$file, 'path' => trim($file['path'], '/'), 'directory' => false]))
        ->sortBy(fn (array $node): string => $node['path'])
        ->values();

    foreach ($sorted as $node) {
        $segments = explode('/', $node['path']);
        $parents = [];

        for ($index = 1; $index < count($segments); $index++) {
            $parents[] = implode('/', array_slice($segments, 0, $index));
        }

        $nodes[] = [
            ...$node,
            'name' => end($segments).($node['directory'] ? '/' : ''),
            'depth' => count($segments) - 1,
            'parents' => $parents,
        ];
    }

    $totalAdditions = collect($files)->sum(fn (array $file): int => (int) ($file['additions'] ?? 0));
    $totalDeletions = collect($files)->sum(fn (array $file): int => (int) ($file['deletions'] ?? 0));
    $statusClasses = [
        'A' => 'bg-ok-bg text-ok-fg',
        'M' => 'bg-warn-bg text-warn-fg',
        'D' => 'bg-risk-bg text-risk-fg',
    ];
@endphp

<aside
    data-file-tree
    aria-label="{{ $heading }}"
    x-data="uiFileTree({ value: @js($value) })"
    x-modelable="value"
    x-on:keydown.window="if ($event.key === 't' && ! ['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName) && ! document.activeElement?.isContentEditable && $refs.filter) { $event.preventDefault(); $refs.filter.focus() }"
    {{ $attributes->class(['flex min-h-0 flex-col bg-card text-ink']) }}
>
    <div class="border-b border-line-soft p-3.5 pb-2.5">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold">{{ $heading }}</span>
            <span class="font-mono text-[11px] text-muted">{{ count($files) }}</span>
            <span class="ml-auto font-mono text-[11px]"><span class="text-ok-fg">+{{ $totalAdditions }}</span> <span class="text-risk-fg">−{{ $totalDeletions }}</span></span>
        </div>
        @if ($filters)
            <label class="mt-2.5 flex h-8 items-center gap-2 rounded-lg border border-line px-2.5 text-[13px] text-muted focus-within:border-ink">
                <x-ui::icon name="search-01" class="size-3.5" />
                <span class="sr-only">{{ __('Filter files') }}</span>
                <input type="search" x-ref="filter" x-model="query" placeholder="{{ __('Filter files') }}" class="min-w-0 flex-1 bg-transparent text-ink outline-none placeholder:text-muted [&::-webkit-search-cancel-button]:hidden" />
                <x-ui::kbd class="px-[5px] py-px text-[10px]">T</x-ui::kbd>
            </label>
            <div role="tablist" aria-label="{{ __('Show') }}" class="mt-2 flex gap-1">
                @foreach (['all' => __('All'), 'findings' => __('With findings'), 'new' => __('New')] as $filterKey => $filterLabel)
                    <button
                        type="button"
                        role="tab"
                        x-on:click="filter = @js($filterKey)"
                        x-bind:aria-selected="(filter === @js($filterKey)).toString()"
                        x-bind:class="filter === @js($filterKey) ? 'border-ink bg-ink text-on-ink' : 'border-line bg-card text-ink-2 hover:border-line-hover'"
                        class="h-[26px] cursor-pointer rounded-[7px] border px-2 text-xs transition"
                    >{{ $filterLabel }}</button>
                @endforeach
            </div>
        @endif
    </div>

    <div role="tree" aria-label="{{ $heading }}" class="min-h-0 flex-1 overflow-y-auto p-1.5 text-[13px]" x-on:keydown="onKeydown($event)">
        @foreach ($nodes as $node)
            @php
                $findings = (int) ($node['findings'] ?? 0);
                $status = $node['status'] ?? 'M';
            @endphp
            <button
                type="button"
                role="treeitem"
                data-tree-node
                data-path="{{ $node['path'] }}"
                data-parents="{{ implode('|', $node['parents']) }}"
                @if ($node['directory'])
                    data-directory
                    x-bind:aria-expanded="isOpen(@js($node['path'])).toString()"
                @else
                    data-status="{{ $status }}"
                    data-findings="{{ $findings }}"
                    x-bind:aria-selected="(value === @js($node['path'])).toString()"
                @endif
                aria-level="{{ $node['depth'] + 1 }}"
                x-show="isVisible($el)"
                x-on:click="activate($el)"
                x-bind:tabindex="isFocusTarget($el) ? 0 : -1"
                x-bind:class="value === $el.dataset.path ? 'bg-wrap font-semibold' : 'hover:bg-subtle'"
                style="padding-left: {{ 8 + min($node['depth'], 4) * 16 }}px"
                class="flex h-[30px] w-full cursor-pointer items-center gap-1.5 rounded-[7px] pr-2 text-left outline-none focus-visible:ring-2 focus-visible:ring-ring"
            >
                <x-ui::icon name="arrow-right-01" @class(['size-3 shrink-0 text-muted transition-transform', 'opacity-0' => ! $node['directory']]) x-bind:class="isOpen($el.closest('[data-tree-node]').dataset.path) && 'rotate-90'" />
                <x-ui::icon :name="$node['directory'] ? 'folder-01' : 'file-01'" @class(['size-[15px] shrink-0', 'text-accent' => $node['directory'], 'text-muted' => ! $node['directory']]) />
                <span @class(['truncate', 'text-muted line-through' => ! $node['directory'] && $status === 'D'])>{{ $node['name'] }}</span>
                @unless ($node['directory'])
                    <span class="ml-auto flex shrink-0 items-center gap-1.5">
                        @if ($findings > 0)
                            <span @class(['inline-flex h-[18px] min-w-[18px] items-center justify-center rounded-full px-[5px] text-[11px] font-semibold text-white', 'bg-risk-fg' => ($node['level'] ?? 'warn') === 'risk', 'bg-ai' => ($node['level'] ?? 'warn') !== 'risk'])>{{ $findings }}<span class="sr-only"> {{ __('findings') }}</span></span>
                        @endif
                        <span class="inline-flex size-3.5 items-center justify-center rounded-[3px] font-mono text-[10px] {{ $statusClasses[$status] ?? $statusClasses['M'] }}">{{ $status }}</span>
                    </span>
                @endunless
            </button>
        @endforeach
        <p x-show="! hasMatches()" x-cloak class="px-2 py-6 text-center text-[13px] text-muted">{{ __('No files match this filter.') }}</p>
    </div>

    <div class="flex gap-3 border-t border-line-soft px-3.5 py-2.5 text-[11px] text-muted">
        @foreach (['A' => __('added'), 'M' => __('modified'), 'D' => __('removed')] as $legendStatus => $legendLabel)
            <span class="flex items-center gap-1"><span class="inline-flex size-3 items-center justify-center rounded-[3px] font-mono text-[9px] {{ $statusClasses[$legendStatus] }}">{{ $legendStatus }}</span>{{ $legendLabel }}</span>
        @endforeach
    </div>
</aside>
