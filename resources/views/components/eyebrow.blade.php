{{--
    Rótulo de seção em Geist Mono, maiúsculas.

    <x-ui::eyebrow>Como funciona</x-ui::eyebrow>
    <x-ui::eyebrow accent>Web, celular e tablet</x-ui::eyebrow>
--}}
@props([
    'accent' => false,
])

<span {{ $attributes->class([
    'font-mono text-xs tracking-[.1em] uppercase',
    $accent ? 'text-accent' : 'text-muted',
]) }}>{{ $slot }}</span>
