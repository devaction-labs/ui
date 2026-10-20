{{--
    Grupo de checkboxes. wire:model no grupo recebe um array com os valores marcados.

    <x-ui::checkbox.group wire:model="channels" label="Avisar por">
        <x-ui::checkbox value="email" label="E-mail" />
        <x-ui::checkbox value="whatsapp" label="WhatsApp" />
    </x-ui::checkbox.group>

    variant: default (lista) · cards (cards de escolha) · pills (pílulas)
--}}
@props([
    'label' => null,
    'description' => null,
    'variant' => 'default',
])

<fieldset
    data-choice-group
    x-data="uiChoiceGroup({ multiple: true })"
    x-modelable="value"
    x-on:change="changed()"
    {{ $attributes->class(['m-0 flex min-w-0 flex-col gap-2 border-0 p-0']) }}
>
    @if ($label)
        <legend class="mb-1 p-0 text-sm font-medium text-ink">{{ $label }}</legend>
    @endif
    @if ($description)
        <x-ui::description class="-mt-1">{{ $description }}</x-ui::description>
    @endif
    <div @class([
        'flex flex-col gap-0.5' => $variant === 'default',
        'grid grid-cols-1 gap-2.5 sm:grid-cols-2' => $variant === 'cards',
        'flex flex-wrap gap-2' => $variant === 'pills',
    ])>
        {{ $slot }}
    </div>
</fieldset>
