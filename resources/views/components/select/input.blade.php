{{-- Busca de um x-ui::select variant="listbox", para o slot input: <x-ui::select.input wire:model.live="search" /> --}}
@props([
    'placeholder' => null,
    'listbox' => null,
])

<x-ui::combobox.input :placeholder="$placeholder ?? __('Filter…')" :listbox="$listbox" :attributes="$attributes" />
