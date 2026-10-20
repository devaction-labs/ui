{{--
    Paginação (API no estilo Flux): recebe o paginator do Laravel.

    <x-ui::pagination :paginator="$contacts" />                 links normais (?page=2)
    <x-ui::pagination :paginator="$contacts" livewire />        botões com setPage() do WithPagination
    <x-ui::pagination :paginator="$contacts" :summary="false" />

    Com paginate() mostra os números (1 2 3 … 9); com simplePaginate() só anterior e próxima.
    on-each-side: quantas páginas aparecem ao lado da atual (padrão 1)
--}}
@props([
    'paginator',
    'livewire' => false,
    'summary' => true,
    'onEachSide' => 1,
])

@php
    $isLengthAware = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $pageName = $paginator->getPageName();
    $current = $paginator->currentPage();
    $last = $isLengthAware ? $paginator->lastPage() : null;

    $pages = [];
    if ($isLengthAware && $last > 1) {
        $window = range(max(1, $current - $onEachSide), min($last, $current + $onEachSide));
        $pages = array_values(array_unique(array_merge([1], $window, [$last])));
        sort($pages);
    }

    $baseLink = 'flex size-[38px] items-center justify-center rounded-[10px] text-sm text-ink transition outline-none focus-visible:ring-2 focus-visible:ring-ring';
    $arrowLink = 'flex size-[38px] items-center justify-center rounded-[10px] border border-line text-ink transition outline-none hover:bg-subtle focus-visible:ring-2 focus-visible:ring-ring';
    $arrowDisabled = 'flex size-[38px] items-center justify-center rounded-[10px] border border-line-soft text-line-hover';
@endphp

@if ($paginator->hasPages())
    <div data-pagination {{ $attributes->class(['flex flex-wrap items-center justify-between gap-3']) }}>
        @if ($summary && $isLengthAware)
            <p class="text-[13px] text-muted">
                Mostrando <span class="font-medium text-ink tabular-nums">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
                de <span class="font-medium text-ink tabular-nums">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
            </p>
        @elseif ($summary)
            <p class="text-[13px] text-muted">Página <span class="font-medium text-ink tabular-nums">{{ $current }}</span></p>
        @endif

        <nav aria-label="Paginação" class="ml-auto flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="{{ $arrowDisabled }}" aria-disabled="true" aria-label="Anterior"><x-ui::icon name="arrow-left-01" class="size-4" /></span>
            @elseif ($livewire)
                <button type="button" wire:click="previousPage(@js($pageName))" wire:loading.attr="disabled" class="{{ $arrowLink }} cursor-pointer" aria-label="Anterior" rel="prev"><x-ui::icon name="arrow-left-01" class="size-4" /></button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="{{ $arrowLink }}" aria-label="Anterior" rel="prev"><x-ui::icon name="arrow-left-01" class="size-4" /></a>
            @endif

            @foreach ($pages as $index => $page)
                @if ($index > 0 && $page - $pages[$index - 1] > 1)
                    <span class="w-6 text-center text-muted" aria-hidden="true">…</span>
                @endif

                @if ($page === $current)
                    <span class="{{ $baseLink }} bg-ink font-medium text-on-ink tabular-nums" aria-current="page">{{ $page }}</span>
                @elseif ($livewire)
                    <button type="button" wire:click="setPage({{ $page }}, @js($pageName))" class="{{ $baseLink }} cursor-pointer tabular-nums hover:bg-wrap" aria-label="Página {{ $page }}">{{ $page }}</button>
                @else
                    <a href="{{ $paginator->url($page) }}" class="{{ $baseLink }} tabular-nums hover:bg-wrap" aria-label="Página {{ $page }}">{{ $page }}</a>
                @endif
            @endforeach

            @if (! $paginator->hasMorePages())
                <span class="{{ $arrowDisabled }}" aria-disabled="true" aria-label="Próxima"><x-ui::icon name="arrow-right-01" class="size-4" /></span>
            @elseif ($livewire)
                <button type="button" wire:click="nextPage(@js($pageName))" wire:loading.attr="disabled" class="{{ $arrowLink }} cursor-pointer" aria-label="Próxima" rel="next"><x-ui::icon name="arrow-right-01" class="size-4" /></button>
            @else
                <a href="{{ $paginator->nextPageUrl() }}" class="{{ $arrowLink }}" aria-label="Próxima" rel="next"><x-ui::icon name="arrow-right-01" class="size-4" /></a>
            @endif
        </nav>
    </div>
@endif
