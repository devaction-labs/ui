{{--
    Card (API no estilo Flux): superfície com borda para agrupar conteúdo.

    <x-ui::card>Conteúdo</x-ui::card>

    <x-ui::card size="sm">
        <x-ui::card.header>
            <x-ui::card.heading>Senha</x-ui::card.heading>
            <x-ui::card.subheading>Alterada há 3 meses</x-ui::card.subheading>
            <x-ui::card.actions><x-ui::button size="sm">Alterar</x-ui::button></x-ui::card.actions>
        </x-ui::card.header>
        <x-ui::card.body>…</x-ui::card.body>
        <x-ui::card.footer>…</x-ui::card.footer>
    </x-ui::card>

    size:    xs · sm · md (padrão) · lg — padding, cantos e espaço entre as partes
    variant: default (borda e fundo do card) · soft (fundo suave, sem borda)
    wrap:    moldura de 6 px em volta, como nos painéis do design
--}}
@props([
    'size' => 'md',
    'variant' => 'default',
    'wrap' => false,
])

@php
    [$paddingClasses, $radiusClasses, $gapClasses] = match ($size) {
        'xs' => ['p-3', 'rounded-xl', 'gap-2'],
        'sm' => ['p-4', 'rounded-[14px]', 'gap-3'],
        'lg' => ['p-8', 'rounded-[20px]', 'gap-5'],
        default => ['p-[22px]', 'rounded-card', 'gap-4'],
    };

    // Tabelas com bleed usam este valor para ir até a borda do card
    $bleedClass = match ($size) {
        'xs' => '[--ui-bleed:0.75rem]',
        'sm' => '[--ui-bleed:1rem]',
        'lg' => '[--ui-bleed:2rem]',
        default => '[--ui-bleed:22px]',
    };

    $classes = [
        'flex flex-col text-ink',
        $paddingClasses,
        $radiusClasses,
        $gapClasses,
        $bleedClass,
        $variant === 'soft' ? 'bg-wrap' : 'border border-line bg-card',
    ];
@endphp

@if ($wrap)
    <div data-card-wrap class="rounded-wrap bg-wrap p-1.5">
        <div data-card {{ $attributes->class($classes) }}>{{ $slot }}</div>
    </div>
@else
    <div data-card {{ $attributes->class($classes) }}>{{ $slot }}</div>
@endif
