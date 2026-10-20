{{--
    Tema barra no topo: navegação horizontal e conteúdo largo. Para até 6 seções, sem hierarquia funda.

    <x-ui::layout.topbar>
        <x-slot:brand><x-ui::logo size="xs">Portal do cliente</x-ui::logo></x-slot:brand>
        <x-slot:nav>
            <x-ui::layout.topbar.item href="/projetos" current>Projetos</x-ui::layout.topbar.item>
            <x-ui::layout.topbar.item href="/faturas">Faturas</x-ui::layout.topbar.item>
        </x-slot:nav>
        <x-slot:actions><x-ui::avatar name="Ana Souza" size="sm" circle /></x-slot:actions>
        …
    </x-ui::layout.topbar>

    width: largura máxima do conteúdo (padrão 1280px)
--}}
@props([
    'width' => '1280px',
])

<div data-layout="topbar" {{ $attributes->class(['flex min-h-screen flex-col bg-frame text-ink']) }}>
    <header class="sticky top-0 z-30 border-b border-line bg-panel/90 backdrop-blur">
        <div class="mx-auto flex h-14 items-center gap-6 px-4 md:px-6" style="max-width: {{ $width }}">
            {{ $brand ?? '' }}
            @isset($nav)
                <nav aria-label="{{ __('Main') }}" class="flex min-w-0 flex-1 items-center gap-1 overflow-x-auto">{{ $nav }}</nav>
            @else
                <span class="flex-1"></span>
            @endisset
            @isset($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    </header>
    <main class="mx-auto w-full flex-1 px-4 py-6 md:px-6" style="max-width: {{ $width }}">{{ $slot }}</main>
</div>
