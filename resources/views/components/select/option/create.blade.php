{{-- Criar opção num x-ui::select variant="listbox" (mesma API do x-ui::combobox.option.create) --}}
@props([
    'minLength' => 1,
    'modal' => null,
])

<x-ui::combobox.option.create :min-length="$minLength" :modal="$modal" :attributes="$attributes">{{ $slot }}</x-ui::combobox.option.create>
