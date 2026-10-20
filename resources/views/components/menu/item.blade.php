{{--
    Item do menu. Com href vira link.

    <x-ui::menu.item icon="download-04" kbd="⌘E" wire:click="export">Exportar CSV</x-ui::menu.item>
    <x-ui::menu.item icon="delete-02" variant="danger">Excluir</x-ui::menu.item>
    <x-ui::menu.item suffix="3">Notificações</x-ui::menu.item>

    variant:   default · danger
    keep-open: não fecha o menu ao clicar
--}}
@props([
    'icon' => null,
    'iconTrailing' => null,
    'kbd' => null,
    'suffix' => null,
    'variant' => 'default',
    'disabled' => false,
    'keepOpen' => false,
    'href' => null,
])

@php
    $iconTrailing ??= $attributes->get('icon:trailing');
    $attributes = $attributes->except(['icon:trailing']);

    $tag = $href ? 'a' : 'button';
    $isDanger = $variant === 'danger';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="button" @endif
    data-menu-item
    role="menuitem"
    tabindex="-1"
    @if ($href && $disabled) aria-disabled="true" @endif
    @if (! $href) @disabled($disabled) @endif
    x-on:click="if (! @js((bool) $keepOpen) && ! $el.closest('[data-keep-open]')) close()"
    {{ $attributes->class([
        'flex h-[38px] w-full shrink-0 cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm whitespace-nowrap outline-none transition-colors',
        'disabled:cursor-not-allowed disabled:opacity-45 aria-disabled:pointer-events-none aria-disabled:opacity-45',
        'text-risk-fg hover:bg-risk-bg focus:bg-risk-bg' => $isDanger,
        'text-ink hover:bg-wrap focus:bg-wrap' => ! $isDanger,
    ]) }}
>
    @if ($icon)
        <x-ui::icon :name="$icon" @class(['size-4', 'text-muted' => ! $isDanger]) />
    @endif
    <span class="min-w-0 flex-1 truncate">{{ $slot }}</span>
    @if ($suffix !== null)
        <span class="text-[13px] text-muted">{{ $suffix }}</span>
    @endif
    @if ($kbd)
        <kbd class="font-mono text-[11px] text-muted">{{ $kbd }}</kbd>
    @endif
    @if ($iconTrailing)
        <x-ui::icon :name="$iconTrailing" class="size-4 text-muted" />
    @endif
</{{ $tag }}>
