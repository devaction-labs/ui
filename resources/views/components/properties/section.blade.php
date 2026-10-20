{{--
    Seção do x-ui::properties: título pequeno, ação opcional e o conteúdo.

    <x-ui::properties.section heading="Rótulos">…</x-ui::properties.section>

    Slot action: botão à direita do título (editar, adicionar)
--}}
@props([
    'heading' => null,
])

<section data-properties-section {{ $attributes->class(['py-3.5']) }}>
    @if ($heading || isset($action))
        <div class="mb-2.5 flex min-h-6 items-center gap-2">
            <h4 class="text-xs font-semibold text-muted">{{ $heading }}</h4>
            @isset($action)
                <div class="ml-auto flex items-center">{{ $action }}</div>
            @endisset
        </div>
    @endif
    <div class="flex flex-col gap-2">{{ $slot }}</div>
</section>
