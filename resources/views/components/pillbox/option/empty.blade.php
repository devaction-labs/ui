{{--
    Lista vazia do pillbox; when-loading troca o texto enquanto o Livewire busca.

    <x-slot:empty><x-ui::pillbox.option.empty when-loading="Buscando…">Nenhuma tag.</x-ui::pillbox.option.empty></x-slot:empty>
--}}
@props([
    'whenLoading' => null,
])

<x-ui::combobox.option.empty :when-loading="$whenLoading" :attributes="$attributes">{{ $slot }}</x-ui::combobox.option.empty>
