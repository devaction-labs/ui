{{--
    Mensagem de lista vazia. Com when-loading, mostra outro texto enquanto o Livewire busca.

    <x-slot:empty>
        <x-ui::combobox.option.empty when-loading="Buscando…">Nenhuma empresa encontrada.</x-ui::combobox.option.empty>
    </x-slot:empty>
--}}
@props([
    'whenLoading' => null,
])

<span data-combobox-empty x-show="! hasResults()" {{ $attributes->class(['px-2.5 py-3 text-[13px] text-muted']) }}>
    @if ($whenLoading)
        <span class="group-has-[[data-loading]]/combobox:hidden">{{ $slot }}</span>
        <span class="hidden group-has-[[data-loading]]/combobox:inline">{{ $whenLoading }}</span>
    @else
        {{ $slot }}
    @endif
</span>
