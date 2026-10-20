{{--
    Opção de um x-ui::select. No select nativo vira <option>; com variant="listbox" ou "combobox",
    vira uma opção da lista (aceita icon, color, meta e selected-label, como o x-ui::combobox.option).
--}}
@aware([
    'variant' => 'default',
])

@props([
    'value' => null,
    'icon' => null,
    'color' => null,
    'meta' => null,
    'search' => null,
    'selectedLabel' => null,
    'filterable' => true,
])

@if (in_array($variant, ['listbox', 'combobox'], true))
    <x-ui::combobox.option :value="$value" :icon="$icon" :color="$color" :meta="$meta" :search="$search" :selected-label="$selectedLabel" :filterable="$filterable" :attributes="$attributes">{{ $slot }}</x-ui::combobox.option>
@else
    <option {{ $value !== null ? $attributes->merge(['value' => $value]) : $attributes }}>{{ $slot }}</option>
@endif
