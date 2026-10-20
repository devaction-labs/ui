{{--
    Criar uma opção a partir do texto digitado.

    <x-ui::pillbox.option.create wire:click="createTag" min-length="2">Criar "<span wire:text="search"></span>"</x-ui::pillbox.option.create>
--}}
@props([
    'minLength' => 1,
    'modal' => null,
])

<x-ui::combobox.option.create :min-length="$minLength" :modal="$modal" :attributes="$attributes">{{ $slot }}</x-ui::combobox.option.create>
