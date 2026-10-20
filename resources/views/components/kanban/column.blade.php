{{--
    Coluna do kanban. Com sort, os cards ficam arrastáveis (wire:sort): o método recebe
    (key do card, posição, id da coluna).

    <x-ui::kanban.column id="review" heading="Em review" count="2" color="#D29A1E" limit="2" sort="moveTask">…</x-ui::kanban.column>

    limit:  limite de trabalho em andamento; mostra "2/3" e fica vermelho ao chegar no limite
    group:  nome do grupo do wire:sort (colunas do mesmo grupo trocam cards)
    count-text: expressão do wire:text para a contagem (ex.: $wire.totals.review.count);
                o número muda na hora ao arrastar, sem esperar o servidor
    footer: slot para o botão de adicionar e totais
--}}
@props([
    'id' => null,
    'heading',
    'count' => null,
    'color' => null,
    'limit' => null,
    'sort' => null,
    'group' => 'kanban',
    'countText' => null,
])

@php
    $isAtLimit = $limit !== null && $count !== null && (int) $count >= (int) $limit;
@endphp

<section
    data-kanban-column
    aria-label="{{ $heading }}"
    {{ $attributes->class(['flex min-w-[260px] flex-1 flex-col gap-2.5 rounded-2xl bg-wrap p-3']) }}
>
    <header class="flex items-center gap-2 px-1 py-0.5">
        @if ($color)
            <span class="size-2 shrink-0 rounded-full" style="background: {{ $color }}" aria-hidden="true"></span>
        @endif
        <h3 class="truncate text-sm font-semibold text-ink">{{ $heading }}</h3>
        @if ($count !== null)
            <span class="font-mono text-xs text-muted" @if ($countText) wire:text="{{ $countText }}" @endif>{{ $count }}</span>
        @endif
        @if ($limit !== null)
            <span
                @class([
                    'ml-auto font-mono text-[11px]',
                    'text-risk-fg' => $isAtLimit,
                    'text-muted' => ! $isAtLimit,
                ])
                @if ($countText)
                    wire:text="({{ $countText }}) + '/{{ (int) $limit }}' + (({{ $countText }}) >= {{ (int) $limit }} ? @js(' '.__('limit')) : '')"
                    x-bind:class="{ 'text-risk-fg': ({{ $countText }}) >= {{ (int) $limit }}, 'text-muted': ({{ $countText }}) < {{ (int) $limit }} }"
                @endif
            >{{ $count ?? 0 }}/{{ $limit }}{{ $isAtLimit ? ' '.__('limit') : '' }}</span>
        @endif
        @isset($actions)
            <span @class(['flex items-center', 'ml-auto' => $limit === null])>{{ $actions }}</span>
        @endisset
    </header>

    <div
        data-kanban-cards
        @if ($sort)
            wire:sort="{{ $sort }}"
            wire:sort:group="{{ $group }}"
            @if ($id !== null) wire:sort:group-id="{{ $id }}" @endif
        @endif
        class="flex min-h-16 flex-col gap-2.5 rounded-xl"
    >
        {{ $slot }}
    </div>

    @isset($footer)
        {{ $footer }}
    @endisset
</section>
