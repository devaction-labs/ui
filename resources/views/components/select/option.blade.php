{{--
    Opção de um x-ui::select. No select nativo vira <option>; com variant="listbox" ou "combobox",
    vira uma opção da lista (aceita icon, color, meta e selected-label, como o x-ui::combobox.option).
--}}
@aware([
    'variant' => 'default',
])

@props([
    'value' => null,
])

@if (in_array($variant, ['listbox', 'combobox'], true))
    <x-ui::combobox.option :value="$value" :attributes="$attributes">{{ $slot }}</x-ui::combobox.option>
@else
    <option {{ $value !== null ? $attributes->merge(['value' => $value]) : $attributes }}>{{ $slot }}</option>
@endif
