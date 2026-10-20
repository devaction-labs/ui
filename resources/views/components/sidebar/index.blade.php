{{--
    Sidebar do app (API no estilo Flux): 236 px aberta, 64 px recolhida (só ícones com tooltip)
    e, abaixo de 768 px, barra superior com o menu em drawer.

    <div class="flex min-h-screen flex-col bg-frame md:flex-row md:gap-3.5 md:p-3.5">
        <x-ui::sidebar>
            <x-slot:brand><x-ui::sidebar.brand name="DevAction" href="/" /></x-slot:brand>
            <x-ui::sidebar.search />
            <x-ui::sidebar.nav>
                <x-ui::sidebar.item icon="home-01" href="/" current>Hoje</x-ui::sidebar.item>
            </x-ui::sidebar.nav>
            <x-ui::sidebar.spacer />
            <x-ui::sidebar.profile name="Alex Nogueira" description="DevAction Labs" />
        </x-ui::sidebar>
        <x-ui::main>…</x-ui::main>
    </div>

    collapsible: botão de recolher ao lado da marca
    sticky:      fica preso na tela ao rolar (desktop)
    O estado vive em $store.uiSidebar (toggle(), close(), collapsed, open).
--}}
@props([
    'label' => 'Principal',
    'collapsible' => true,
    'sticky' => true,
])

<div data-sidebar-root class="contents">
    <div class="sticky top-0 z-30 flex items-center gap-3 border-b border-line-soft bg-frame/90 px-4 py-3 backdrop-blur md:hidden">
        <x-ui::sidebar.toggle />
        @isset($brand)
            {{ $brand }}
        @endisset
        @isset($mobile)
            <div class="ml-auto flex items-center gap-2">{{ $mobile }}</div>
        @endisset
    </div>

    <div
        x-data
        x-show="$store.uiSidebar.open"
        x-cloak
        x-transition.opacity
        x-on:click="$store.uiSidebar.close()"
        class="fixed inset-0 z-40 bg-black/40 md:hidden"
        aria-hidden="true"
    ></div>

    <nav
        data-sidebar
        aria-label="{{ $label }}"
        x-data
        x-bind:data-collapsed="$store.uiSidebar.isCollapsed() ? '' : null"
        x-bind:class="$store.uiSidebar.open && 'translate-x-0!'"
        x-on:keydown.escape.window="$store.uiSidebar.close()"
        {{ $attributes->class([
            'group/sidebar fixed inset-y-0 left-0 z-50 flex w-[272px] -translate-x-full flex-col gap-[18px] overflow-y-auto bg-frame px-3 py-4 transition-[translate,width] duration-200 motion-reduce:transition-none',
            'md:z-auto md:w-[236px] md:shrink-0 md:translate-x-0 md:overflow-visible md:bg-transparent md:px-0.5 md:py-2 md:data-collapsed:w-16',
            'md:sticky md:top-3.5 md:h-[calc(100dvh-28px)]' => $sticky,
            'md:relative md:self-stretch' => ! $sticky,
        ]) }}
    >
        @isset($brand)
            <div class="flex items-center justify-between gap-2 px-1.5 in-data-collapsed:flex-col in-data-collapsed:px-0">
                {{ $brand }}
                @if ($collapsible)
                    <x-ui::sidebar.toggle class="max-md:hidden" />
                @endif
            </div>
        @endisset

        {{ $slot }}
    </nav>
</div>
