{{--
    Grupo do menu de filtros: item que abre um submenu com as opções.

    <x-ui::filter-menu.group name="area" label="Área" icon="folder-01">…</x-ui::filter-menu.group>

    single: escolha única (menuitemradio) em vez de várias (menuitemcheckbox)
--}}
@props([
    'name',
    'label',
    'icon' => null,
    'single' => false,
])

<div class="relative" x-on:mouseleave="if (activeGroup === @js($name) && ! $el.contains(document.activeElement)) activeGroup = null">
    <button
        type="button"
        role="menuitem"
        aria-haspopup="menu"
        data-filter-group-trigger="{{ $name }}"
        x-bind:aria-expanded="activeGroup === @js($name)"
        x-on:click="openGroup(@js($name), true)"
        x-on:mouseenter="openGroup(@js($name))"
        x-on:keydown.arrow-right.prevent="openGroup(@js($name), true)"
        x-on:keydown.arrow-down.prevent="focusSibling(1)"
        x-on:keydown.arrow-up.prevent="focusSibling(-1)"
        class="flex min-h-10 w-full cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm text-ink outline-none hover:bg-wrap focus-visible:bg-wrap"
        x-bind:class="activeGroup === @js($name) && 'bg-wrap'"
    >
        @if ($icon)
            <x-ui::icon :name="$icon" class="size-4 shrink-0 text-ink-3" />
        @endif
        <span class="flex-1">{{ $label }}</span>
        <span
            x-show="groupCount(@js($name))"
            x-text="groupCount(@js($name))"
            class="flex h-[18px] min-w-[18px] items-center justify-center rounded-full border border-line bg-wrap px-[5px] font-mono text-[10px]"
        ></span>
        <x-ui::icon name="arrow-right-01" class="size-3.5 shrink-0 text-muted" />
    </button>

    <div
        role="menu"
        aria-label="{{ $label }}"
        data-filter-group="{{ $name }}"
        x-show="activeGroup === @js($name)"
        x-cloak
        x-on:keydown.arrow-left.prevent.stop="escape()"
        class="absolute top-0 left-full z-10 ml-1.5 flex max-h-72 w-[230px] flex-col overflow-y-auto rounded-[14px] border border-line bg-card p-1.5 shadow-[0_16px_40px_rgb(24_24_27/0.14)]"
        data-single="{{ $single ? 'true' : 'false' }}"
    >
        {{ $slot }}
    </div>
</div>
