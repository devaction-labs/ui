{{--
    Opção do pillbox (mesma API do x-ui::combobox.option).

    <x-ui::pillbox.option value="vip" icon="star">VIP</x-ui::pillbox.option>
    <x-ui::pillbox.option :value="$user->id" :selected-label="$user->first_name">{{ $user->name }} · {{ $user->email }}</x-ui::pillbox.option>

    selected-label: texto da pílula quando escolhida · filterable: false nunca some no filtro
--}}
@props([
    'value' => null,
    'icon' => null,
    'color' => null,
    'meta' => null,
    'search' => null,
    'selectedLabel' => null,
    'filterable' => true,
])

<x-ui::combobox.option :value="$value" :icon="$icon" :color="$color" :meta="$meta" :search="$search" :selected-label="$selectedLabel" :filterable="$filterable" :attributes="$attributes">{{ $slot }}</x-ui::combobox.option>
