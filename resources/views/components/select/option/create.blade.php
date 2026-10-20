{{-- Criar opção num x-ui::select variant="listbox" (mesma API do x-ui::combobox.option.create) --}}
<x-ui::combobox.option.create :attributes="$attributes">{{ $slot }}</x-ui::combobox.option.create>
