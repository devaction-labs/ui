{{--
    Grupo de itens com título. expandable recolhe o grupo num clique.

    <x-ui::sidebar.group heading="Vendas">…</x-ui::sidebar.group>
    <x-ui::sidebar.group heading="Relatórios" expandable :expanded="false">…</x-ui::sidebar.group>
--}}
@props([
    'heading' => null,
    'expandable' => false,
    'expanded' => true,
])

<div data-sidebar-group @if ($expandable) x-data="{ expanded: @js((bool) $expanded) }" @endif {{ $attributes->class(['flex flex-col gap-0.5']) }}>
    @if ($heading)
        @if ($expandable)
            <button
                type="button"
                x-on:click="expanded = ! expanded"
                x-bind:aria-expanded="expanded"
                class="flex h-7 cursor-pointer items-center gap-1 px-2.5 font-mono text-[11px] tracking-[0.08em] text-muted uppercase transition hover:text-ink in-data-collapsed:hidden"
            >
                <span class="flex-1 text-left">{{ $heading }}</span>
                <x-ui::icon name="arrow-down-01" class="size-3.5 transition" x-bind:class="! expanded && '-rotate-90'" />
            </button>
        @else
            <span class="flex h-7 items-center px-2.5 font-mono text-[11px] tracking-[0.08em] text-muted uppercase in-data-collapsed:hidden">{{ $heading }}</span>
        @endif
        <span class="mx-auto hidden h-px w-6 bg-line in-data-collapsed:my-2 in-data-collapsed:block" aria-hidden="true"></span>
    @endif

    <div class="flex flex-col gap-0.5" @if ($expandable) x-show="expanded || $store.uiSidebar.isCollapsed()" x-collapse @endif>
        {{ $slot }}
    </div>
</div>
