{{--
    Aba.

    <x-ui::tab name="geral">Visão geral</x-ui::tab>
    <x-ui::tab name="prs" icon="git-pull-request" badge="3">Pull Requests</x-ui::tab>
    <x-ui::tab name="mes" badge="4" badge:color="red" selected>Vencendo no mês</x-ui::tab>
    <x-ui::tab name="arquivo" disabled>Arquivados</x-ui::tab>

    name:     liga a aba ao tab.panel de mesmo nome e ao wire:model
    selected: aba ativa no primeiro carregamento (sem ele, a primeira habilitada)
--}}
@aware([
    'variant' => 'default',
    'size' => 'base',
])

@props([
    'name' => null,
    'icon' => null,
    'iconTrailing' => null,
    'badge' => null,
    'selected' => false,
    'disabled' => false,
])

@php
    $iconTrailing ??= $attributes->get('icon:trailing');
    $badgeColor = $attributes->get('badge:color');
    $attributes = $attributes->except(['icon:trailing', 'badge:color']);

    $isSmall = $size === 'sm';

    $variantClasses = match ($variant) {
        'segmented' => [
            'rounded-[9px] border border-transparent text-ink-3 hover:text-ink',
            'aria-selected:border-line aria-selected:bg-card aria-selected:font-medium aria-selected:text-ink aria-selected:shadow-[0_1px_2px_rgb(24_24_27/0.06)]',
            $isSmall ? 'h-[30px] px-2.5 text-[13px]' : 'h-[38px] px-3.5 text-sm',
        ],
        'pills' => [
            'rounded-full text-ink-3 hover:bg-wrap hover:text-ink',
            'aria-selected:bg-ink aria-selected:font-medium aria-selected:text-on-ink aria-selected:hover:bg-ink',
            $isSmall ? 'h-7 px-3 text-[13px]' : 'h-8 px-3.5 text-sm',
        ],
        default => [
            '-mb-px border-b-2 border-transparent text-muted hover:text-ink',
            'aria-selected:border-ink aria-selected:font-semibold aria-selected:text-ink',
            $isSmall ? 'h-9 px-3 text-[13px]' : 'h-11 px-3.5 text-sm',
        ],
    };

    $badgeColorClasses = match ($badgeColor) {
        'red', 'rose' => 'text-risk-fg',
        'amber', 'orange', 'yellow' => 'text-warn-fg',
        'green', 'emerald' => 'text-ok-fg',
        'blue', 'sky' => 'text-info-fg',
        default => 'text-muted',
    };
@endphp

<button
    type="button"
    role="tab"
    data-tab
    @if ($name !== null) data-tab-name="{{ $name }}" @endif
    @if ($selected) data-selected @endif
    aria-selected="{{ $selected ? 'true' : 'false' }}"
    tabindex="{{ $selected ? '0' : '-1' }}"
    x-bind:aria-selected="(selected === $el.dataset.tabName).toString()"
    x-bind:tabindex="selected === $el.dataset.tabName ? 0 : -1"
    x-bind:id="$id('ui-tab', $el.dataset.tabName)"
    x-bind:aria-controls="$id('ui-tab-panel', $el.dataset.tabName)"
    x-on:click="select($el.dataset.tabName)"
    @disabled($disabled)
    {{ $attributes->class([
        'inline-flex shrink-0 cursor-pointer items-center justify-center gap-2 whitespace-nowrap transition outline-none',
        'focus-visible:ring-2 focus-visible:ring-ring',
        'disabled:cursor-not-allowed disabled:opacity-45',
        ...$variantClasses,
    ]) }}
>
    @if ($icon)
        <x-ui::icon :name="$icon" :class="$isSmall ? 'size-3.5' : 'size-4'" />
    @endif
    {{ $slot }}
    @if ($badge !== null)
        <span class="font-mono text-[11px] font-normal {{ $badgeColorClasses }}">{{ $badge }}</span>
    @endif
    @if ($iconTrailing)
        <x-ui::icon :name="$iconTrailing" :class="$isSmall ? 'size-3.5' : 'size-4'" />
    @endif
</button>
