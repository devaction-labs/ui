{{--
    Submenu: abre ao lado ao passar o mouse, clicar ou apertar →; ← volta.

    <x-ui::menu.submenu heading="Mover para" icon="folder-01">
        <x-ui::menu.item>SGE</x-ui::menu.item>
        <x-ui::menu.item>Hub API</x-ui::menu.item>
    </x-ui::menu.submenu>
--}}
@props([
    'heading',
    'icon' => null,
])

<div
    data-menu-submenu
    x-data="{ subOpen: false }"
    x-on:mouseenter="subOpen = true"
    x-on:mouseleave="subOpen = false"
    {{ $attributes->class(['relative']) }}
>
    <button
        type="button"
        data-menu-item
        data-submenu-trigger
        role="menuitem"
        aria-haspopup="menu"
        aria-expanded="false"
        x-bind:aria-expanded="subOpen.toString()"
        tabindex="-1"
        x-on:click="subOpen = true"
        class="flex h-[38px] w-full cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm text-ink outline-none transition-colors hover:bg-wrap focus:bg-wrap aria-expanded:bg-wrap"
    >
        @if ($icon)
            <x-ui::icon :name="$icon" class="size-4 text-muted" />
        @endif
        <span class="min-w-0 flex-1 truncate">{{ $heading }}</span>
        <x-ui::icon name="arrow-right-01" class="size-4 text-muted" />
    </button>

    <div
        data-menu
        role="menu"
        x-data="uiMenu({ submenu: true })"
        x-show="subOpen"
        x-ui-float="{ placement: 'right', gap: 4, offset: -6, reference: $el.parentElement }"
        x-cloak
        x-on:keydown="onKeydown($event)"
        class="absolute top-0 left-full z-50 -mt-1.5 ml-1 flex w-56 flex-col rounded-[14px] border border-line bg-card p-1.5 shadow-pop"
    >
        {{ $slot }}
    </div>
</div>
