{{--
    Tema clássico: sidebar com grupos e o conteúdo num painel recuado, com header (breadcrumb e ações) no topo.
    É a casca do DevAction IA. Com collapsed vira o tema Trilho (só ícones; ⌘B alterna).

    <x-ui::layout.classic>
        <x-slot:sidebar><x-ui::sidebar>…</x-ui::sidebar></x-slot:sidebar>
        <x-slot:header>
            <x-ui::breadcrumbs>…</x-ui::breadcrumbs>
        </x-slot:header>
        <x-slot:actions><x-ui::button size="sm" icon="notification-01" variant="ghost" :aria-label="__('Notifications')" /></x-slot:actions>
        <x-ui::page-header heading="Projetos" />
        …
    </x-ui::layout.classic>

    collapsed: começa recolhido (tema Trilho)
--}}
@props([
    'collapsed' => false,
])

<div
    data-layout="classic"
    x-data
    @if ($collapsed) x-init="$store.uiSidebar.collapsed = true" @endif
    x-on:keydown.cmd.b.window.prevent="$store.uiSidebar.toggle()"
    x-on:keydown.ctrl.b.window.prevent="$store.uiSidebar.toggle()"
    {{ $attributes->class(['flex min-h-screen flex-col bg-frame text-ink md:flex-row md:gap-2.5 md:p-2.5']) }}
>
    {{ $sidebar ?? '' }}

    <main data-layout-panel class="flex min-w-0 flex-1 flex-col bg-panel md:rounded-[14px] md:border md:border-line md:shadow-[0_1px_2px_rgb(24_24_27/0.06)]">
        @if (isset($header) || isset($actions))
            <div class="flex h-[52px] shrink-0 items-center gap-2 border-b border-line-soft px-3 md:px-4">
                <x-ui::sidebar.toggle class="max-md:hidden" />
                <div class="flex min-w-0 flex-1 items-center gap-2">{{ $header ?? '' }}</div>
                @isset($actions)
                    <div class="flex shrink-0 items-center gap-1.5">{{ $actions }}</div>
                @endisset
            </div>
        @endif
        <div class="min-h-0 flex-1 px-4 py-5 md:px-6">{{ $slot }}</div>
    </main>
</div>
