{{--
    Corpo do card.

    flush: tira o padding do card nesta parte (tabelas e listas de borda a borda)
--}}
@aware([
    'size' => 'md',
])

@props([
    'flush' => false,
])

@php
    $flushClasses = match ($size) {
        'xs' => '-mx-3',
        'sm' => '-mx-4',
        'lg' => '-mx-8',
        default => '-mx-[22px]',
    };
@endphp

<div {{ $attributes->class(['min-w-0 text-sm leading-normal text-ink-2', $flushClasses => $flush]) }}>
    {{ $slot }}
</div>
