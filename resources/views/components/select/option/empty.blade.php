{{-- Lista vazia num x-ui::select variant="listbox"; when-loading troca o texto enquanto busca --}}
<x-ui::combobox.option.empty :attributes="$attributes">{{ $slot }}</x-ui::combobox.option.empty>
