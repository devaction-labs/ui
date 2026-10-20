{{--
    Campo de texto dentro do pillbox com variant="combobox". Use no slot input para levar o texto ao servidor:

    <x-slot:input><x-ui::pillbox.input wire:model.live="search" placeholder="Escolha as tags…" /></x-slot:input>

    ⌫ no campo vazio remove a última pílula. Os outros atributos vão para o input.
--}}
@props([
    'placeholder' => null,
    'listbox' => null,
])

<input
    type="text"
    data-combobox-search
    x-model="query"
    x-on:focus="show()"
    x-on:input="open = true"
    x-on:keydown.arrow-down.prevent="open ? move(1) : show()"
    x-on:keydown.arrow-up.prevent="move(-1)"
    x-on:keydown.enter.prevent="chooseActive()"
    x-on:keydown.backspace="if (query === '' && selectedValues().length) { unselect(selectedValues().at(-1)) }"
    x-on:keydown.tab="close()"
    x-bind:placeholder="selectedValues().length ? '' : @js($placeholder)"
    role="combobox"
    aria-autocomplete="list"
    @if ($listbox) aria-controls="{{ $listbox }}" @endif
    x-bind:aria-expanded="open"
    autocomplete="off"
    {{ $attributes->class(['h-[30px] min-w-[90px] flex-1 bg-transparent px-1 text-sm text-ink outline-none placeholder:text-muted']) }}
/>
