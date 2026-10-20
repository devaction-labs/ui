{{--
    Lista vazia do pillbox; when-loading troca o texto enquanto o Livewire busca.

    <x-slot:empty><x-ui::pillbox.option.empty when-loading="Buscando…">Nenhuma tag.</x-ui::pillbox.option.empty></x-slot:empty>
--}}
<x-ui::combobox.option.empty :attributes="$attributes">{{ $slot }}</x-ui::combobox.option.empty>
