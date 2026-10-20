{{--
    Linha de texto do skeleton. Linhas seguidas já ficam espaçadas como um parágrafo.

    <x-ui::skeleton.line />
    <x-ui::skeleton.line class="w-1/2" />
    <x-ui::skeleton.line size="lg" />

    size:    base (12 px, padrão) · lg (16 px)
    animate: shimmer · pulse — herdado do skeleton.group
--}}
@aware([
    'animate' => null,
])

@props([
    'size' => 'base',
])

@php($attributes = $attributes->except('animate'))

<div data-skeleton-line aria-hidden="true" {{ $attributes->class([
    'py-[3px]',
    'w-full' => ! preg_match('/(^|\s)w-/', (string) $attributes->get('class')),
]) }}>
    <div @class([
        'w-full rounded-[4px]',
        $size === 'lg' ? 'h-4' : 'h-3',
        'bg-line' => $animate !== 'shimmer',
        'skeleton-shimmer animate-shimmer' => $animate === 'shimmer',
        'animate-pulse' => $animate === 'pulse',
        'motion-reduce:animate-none',
    ])></div>
</div>
