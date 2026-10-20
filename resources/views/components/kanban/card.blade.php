{{--
    Card do kanban. key liga o card ao wire:sort e ao wire:key.

    <x-ui::kanban.card key="42" due="sex 16" assignee="Ana Souza">
        Relatório mensal do tesoureiro
        <x-slot:tags><x-ui::tag size="sm" color="info">SGE</x-ui::tag></x-slot:tags>
    </x-ui::kanban.card>

    done:    risca o título (coluna Feito)
    href:    o card vira link
    overdue: prazo em vermelho
--}}
@props([
    'key' => null,
    'due' => null,
    'assignee' => null,
    'done' => false,
    'overdue' => false,
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'article';
@endphp

<{{ $tag }}
    data-kanban-card
    @if ($href) href="{{ $href }}" @endif
    @if ($key !== null)
        wire:key="kanban-card-{{ $key }}"
        wire:sort:item="{{ $key }}"
    @endif
    {{ $attributes->class([
        'flex cursor-grab flex-col gap-2.5 rounded-xl border border-line bg-card p-3.5 text-left transition-shadow outline-none hover:shadow-[0_4px_14px_rgb(24_24_27/0.08)] focus-visible:ring-2 focus-visible:ring-ring active:cursor-grabbing',
        'opacity-70' => $done,
    ]) }}
>
    <span @class([
        'text-sm leading-[1.35] font-medium',
        'text-ink' => ! $done,
        'text-muted line-through' => $done,
    ])>{{ $slot }}</span>

    @isset($tags)
        <span class="flex flex-wrap gap-1.5">{{ $tags }}</span>
    @endisset

    @if ($due || $assignee)
        <span class="flex items-center justify-between gap-2 text-xs text-muted">
            <span @class(['text-risk-fg' => $overdue])>{{ $due }}</span>
            @if ($assignee)
                <x-ui::avatar :name="$assignee" size="xs" circle :tooltip="$assignee" />
            @endif
        </span>
    @endif
</{{ $tag }}>
