{{--
    Grupo de radios (role radiogroup). wire:model no grupo recebe o valor escolhido.

    <x-ui::radio.group wire:model="model" label="Modelo do contrato" name="model">
        <x-ui::radio value="fixo" label="Escopo fechado" description="Valor fixo por marco." />
        <x-ui::radio value="horas" label="Banco de horas" description="Cobra o que foi usado no mês." />
    </x-ui::radio.group>

    variant: default (lista) · cards (cards de escolha)
    name:    nome compartilhado pelos radios, para formulários comuns
--}}
@props([
    'label' => null,
    'description' => null,
    'variant' => 'default',
    'name' => null,
])

<fieldset
    data-choice-group
    role="radiogroup"
    x-data="uiChoiceGroup({ multiple: false })"
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
    ])>
        {{ $slot }}
    </div>
</fieldset>
