{{--
    Busca dentro da lista do pillbox (com searchable ou no slot search).

    <x-slot:search><x-ui::pillbox.search wire:model.live="search" placeholder="Buscar tags…" /></x-slot:search>
--}}
@props([
    'placeholder' => null,
    'listbox' => null,
])

<x-ui::combobox.input :placeholder="$placeholder ?? __('Filter…')" :listbox="$listbox" :attributes="$attributes" />
