{{--
    Título (API no estilo Flux).

    <x-ui::heading size="lg">Atualizar perfil</x-ui::heading>
    <x-ui::heading level="1" size="xl">Contratos</x-ui::heading>

    size:  base (padrão) · lg · xl
    level: 1 a 6 vira <h1>…<h6>; sem level é um <div>
--}}
@props([
    'size' => 'base',
    'level' => null,
])

@php
    $tag = $level !== null && in_array((int) $level, [1, 2, 3, 4, 5, 6], true) ? 'h'.(int) $level : 'div';
@endphp

<{{ $tag }} data-heading {{ $attributes->class([
    'font-semibold text-ink',
    'text-[15px]' => $size === 'base',
    'text-lg tracking-[-0.01em]' => $size === 'lg',
    'text-2xl tracking-[-0.02em]' => $size === 'xl',
]) }}>{{ $slot }}</{{ $tag }}>
