{{--
    Lista de itens da sidebar.

    <x-ui::sidebar.nav>…</x-ui::sidebar.nav>
--}}
<div data-sidebar-nav {{ $attributes->class(['flex flex-col gap-0.5']) }}>
    {{ $slot }}
</div>
