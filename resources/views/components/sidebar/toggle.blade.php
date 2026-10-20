{{--
    Botão que abre o menu no celular e recolhe a sidebar no desktop.

    <x-ui::sidebar.toggle />
--}}
<button
    type="button"
    data-sidebar-toggle
    x-data
    x-on:click="$store.uiSidebar.toggle()"
    x-bind:aria-expanded="$store.uiSidebar.desktop ? String(! $store.uiSidebar.collapsed) : String($store.uiSidebar.open)"
    x-bind:aria-label="$store.uiSidebar.desktop ? ($store.uiSidebar.collapsed ? @js(__('Expand menu')) : @js(__('Collapse menu'))) : @js(__('Open menu'))"
    aria-label="{{ __('Toggle menu') }}"
    {{ $attributes->class(['flex size-9 shrink-0 cursor-pointer items-center justify-center rounded-[10px] text-ink-3 transition hover:bg-wrap hover:text-ink']) }}
>
    <x-ui::icon name="menu-01" class="size-5 md:hidden" />
    <x-ui::icon name="sidebar-left" class="size-[18px] max-md:hidden" />
</button>
