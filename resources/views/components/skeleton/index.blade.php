{{--
    Skeleton (API no estilo Flux): bloco que ocupa o lugar do conteúdo enquanto carrega.
    O formato vem das classes: tamanho, cantos e largura.

    <x-ui::skeleton class="size-10 rounded-full" />
    <x-ui::skeleton animate="shimmer" class="aspect-[4/1] w-full rounded-lg" />

    animate: shimmer · pulse — sem animação por padrão; herdado do skeleton.group
--}}
@aware([
    'animate' => null,
])

@php($attributes = $attributes->except('animate'))

<div
    data-skeleton
    aria-hidden="true"
    {{ $attributes->class([
        'rounded-md' => ! preg_match('/(^|\s)rounded(-|\s|$)/', (string) $attributes->get('class')),
        'bg-line-soft' => $animate !== 'shimmer',
        'skeleton-shimmer animate-shimmer' => $animate === 'shimmer',
        'animate-pulse' => $animate === 'pulse',
        'motion-reduce:animate-none',
    ]) }}
>{{ $slot }}</div>
