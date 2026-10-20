{{--
    Item da sidebar. Recolhida, mostra só o ícone com o nome num tooltip.

    <x-ui::sidebar.item icon="home-01" href="/" current>Hoje</x-ui::sidebar.item>
    <x-ui::sidebar.item icon="git-pull-request" href="/prs" badge="4">Pull Requests</x-ui::sidebar.item>
    <x-ui::sidebar.item icon="user" href="/contatos" wire:navigate>Contatos</x-ui::sidebar.item>

    current: página atual (fundo branco, aria-current="page")
    badge:   contador à direita; badge:color risk (padrão) · plain · ai
--}}
@props([
    'icon' => null,
    'href' => null,
    'current' => false,
    'badge' => null,
])

@php
    $badgeColor = $attributes->get('badge:color', 'risk');
    $attributes = $attributes->except(['badge:color']);
    $tag = $href ? 'a' : 'button';
    $label = trim(strip_tags((string) $slot));
    $badgeClasses = match ($badgeColor) {
        'plain' => 'border-line bg-wrap text-ink-3',
        'ai' => 'border-warn-line bg-warn-bg text-warn-fg',
        default => 'border-risk-line bg-risk-bg text-risk-fg',
    };
@endphp

<{{ $tag }}
    data-sidebar-item
    @if ($href) href="{{ $href }}" @else type="button" @endif
    @if ($current) aria-current="page" @endif
    {{ $attributes->class([
        'group/item relative flex h-[42px] shrink-0 items-center gap-3 rounded-[10px] border px-2.5 text-left text-sm transition outline-none focus-visible:ring-2 focus-visible:ring-ring',
        'in-data-collapsed:justify-center in-data-collapsed:px-0',
        'border-line bg-card font-medium text-ink shadow-[0_1px_2px_rgb(24_24_27/0.04)]' => $current,
        'cursor-pointer border-transparent text-ink-2 hover:bg-wrap hover:text-ink' => ! $current,
    ]) }}
>
    @if ($icon)
        <x-ui::icon :name="$icon" class="size-[18px] shrink-0" />
    @endif
    <span class="flex-1 truncate whitespace-nowrap in-data-collapsed:sr-only">{{ $slot }}</span>
    @if ($badge !== null)
        <span class="rounded-[5px] border px-1.5 py-px font-mono text-[10px] {{ $badgeClasses }} in-data-collapsed:absolute in-data-collapsed:-top-1 in-data-collapsed:-right-1 in-data-collapsed:px-1">{{ $badge }}</span>
    @endif
    <span
        aria-hidden="true"
        class="pointer-events-none invisible absolute top-1/2 left-full z-50 ml-2.5 -translate-y-1/2 rounded-lg bg-ink px-2 py-1 text-xs font-medium whitespace-nowrap text-on-ink opacity-0 transition-opacity in-data-collapsed:group-hover/item:visible in-data-collapsed:group-hover/item:opacity-100 in-data-collapsed:group-focus-visible/item:visible in-data-collapsed:group-focus-visible/item:opacity-100"
    >{{ $label }}</span>
</{{ $tag }}>
