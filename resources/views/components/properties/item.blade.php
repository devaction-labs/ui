{{--
    Linha de rótulo e valor dentro de uma seção do x-ui::properties.

    <x-ui::properties.item label="Prazo">qui 15 · amanhã</x-ui::properties.item>
    <x-ui::properties.item label="Story points" mono>5</x-ui::properties.item>

    mono:    valor em Geist Mono
    stacked: rótulo em cima e valor embaixo (valores longos)
--}}
@props([
    'label' => null,
    'mono' => false,
    'stacked' => false,
])

<div data-properties-item {{ $attributes->class(['flex min-w-0 gap-3 text-[13px]', 'flex-col gap-1' => $stacked, 'items-center justify-between' => ! $stacked]) }}>
    <span class="shrink-0 text-muted">{{ $label }}</span>
    <span @class(['min-w-0 font-medium text-ink', 'font-mono font-normal' => $mono, 'text-right' => ! $stacked])>{{ $slot }}</span>
</div>
