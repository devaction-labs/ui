{{--
    Opção do pillbox (mesma API do x-ui::combobox.option).

    <x-ui::pillbox.option value="vip" icon="star">VIP</x-ui::pillbox.option>
    <x-ui::pillbox.option :value="$user->id" :selected-label="$user->first_name">{{ $user->name }} · {{ $user->email }}</x-ui::pillbox.option>

    selected-label: texto da pílula quando escolhida · filterable: false nunca some no filtro
--}}
<x-ui::combobox.option :attributes="$attributes">{{ $slot }}</x-ui::combobox.option>
