{{--
    Grade de stats.

    <x-ui::stat.group columns="4">
        <x-ui::stat label="Pronto pra entregar" value="78,4" unit="%" />
        …
    </x-ui::stat.group>

    <x-ui::stat.group columns="5" divided>   faixa única com divisórias, como na home do kit
        <x-ui::stat label="componentes" value="58" size="sm" />
        …
    </x-ui::stat.group>

    columns: 2 · 3 · 4 · 5 — vira 2 colunas no celular
    divided: um card só, com divisórias entre os stats
--}}
@props([
    'columns' => 4,
    'divided' => false,
])

@php
    $columnClasses = match ((int) $columns) {
        2 => 'sm:grid-cols-2',
        3 => 'sm:grid-cols-3',
        5 => 'md:grid-cols-5',
        default => 'md:grid-cols-4',
    };
@endphp

<div
    data-stat-group
    {{ $attributes->class([
        'grid grid-cols-2',
        $columnClasses,
        'gap-3.5' => ! $divided,
        'overflow-hidden rounded-card border border-line bg-card *:border-line-soft max-md:*:border-b md:*:not-last:border-r' => $divided,
    ]) }}
>
    {{ $slot }}
</div>
