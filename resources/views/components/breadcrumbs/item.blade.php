{{--
    Item do breadcrumbs. Com href vira link; sem href é a página atual (aria-current="page").

    <x-ui::breadcrumbs.item href="/" icon="home-01" />
    <x-ui::breadcrumbs.item href="/projetos" wire:navigate>Projetos</x-ui::breadcrumbs.item>
    <x-ui::breadcrumbs.item icon="more-horizontal" aria-label="Mais níveis" />
--}}
@aware([
    'separator' => 'slash',
])

@props([
    'href' => null,
    'icon' => null,
    'current' => null,
])

@php
    $hasText = $slot->isNotEmpty();
    $isCurrent = $current ?? ($href === null && $hasText);
@endphp

<li data-breadcrumbs-item class="group/crumb flex min-w-0 items-center gap-[inherit]">
    <span aria-hidden="true" class="text-line-hover group-first/crumb:hidden">
        @if ($separator === 'chevron')
            <x-ui::icon name="arrow-right-01" class="size-3.5" />
        @else
            /
        @endif
    </span>

    @if ($href)
        <a
            href="{{ $href }}"
            @if ($isCurrent) aria-current="page" @endif
            {{ $attributes->class([
                'inline-flex min-w-0 items-center gap-1.5 rounded transition outline-none hover:text-ink focus-visible:ring-2 focus-visible:ring-ring',
                'text-ink' => $isCurrent,
            ]) }}
        >
            @if ($icon)
                <x-ui::icon :name="$icon" class="size-4 shrink-0" />
            @endif
            @if ($hasText)
                <span class="truncate">{{ $slot }}</span>
            @endif
        </a>
    @else
        <span
            @if ($isCurrent) aria-current="page" @endif
            {{ $attributes->class([
                'inline-flex min-w-0 items-center gap-1.5',
                'text-ink' => $isCurrent,
            ]) }}
        >
            @if ($icon)
                <x-ui::icon :name="$icon" class="size-4 shrink-0" />
            @endif
            @if ($hasText)
                <span class="truncate">{{ $slot }}</span>
            @endif
        </span>
    @endif
</li>
