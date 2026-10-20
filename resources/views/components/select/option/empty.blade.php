{{-- Lista vazia num x-ui::select variant="listbox"; when-loading troca o texto enquanto busca --}}
@props([
    'whenLoading' => null,
])

<x-ui::combobox.option.empty :when-loading="$whenLoading" :attributes="$attributes">{{ $slot }}</x-ui::combobox.option.empty>
