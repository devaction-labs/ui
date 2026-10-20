{{--
    Tema lista e detalhe: navegação, lista e o item aberto. A lista rola sozinha e mantém a posição ao trocar de item.

    <x-ui::layout.split>
        <x-slot:sidebar><x-ui::sidebar>…</x-ui::sidebar></x-slot:sidebar>
        <x-slot:list>…itens…</x-slot:list>
        …detalhe do item aberto…
    </x-ui::layout.split>

    list-width: largura da lista (padrão 340px)
--}}
@props([
    'listWidth' => '340px',
])

<div data-layout="split" {{ $attributes->class(['flex min-h-screen flex-col bg-frame text-ink md:h-screen md:flex-row md:gap-2.5 md:p-2.5']) }}>
    {{ $sidebar ?? '' }}

    <div class="flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden bg-panel md:flex-row md:rounded-[14px] md:border md:border-line">
        <section aria-label="{{ __('List') }}" class="flex shrink-0 flex-col border-line-soft max-md:max-h-[45vh] max-md:border-b md:border-r" style="width: min(100%, {{ $listWidth }})">
            @isset($listHeader)
                <div class="shrink-0 border-b border-line-soft p-3">{{ $listHeader }}</div>
            @endisset
            <div class="min-h-0 flex-1 overflow-y-auto">{{ $list ?? '' }}</div>
        </section>
        <section aria-label="{{ __('Details') }}" class="min-h-0 min-w-0 flex-1 overflow-y-auto px-4 py-5 md:px-6">{{ $slot }}</section>
    </div>
</div>
