{{--
    Controle segmentado: escolha única entre poucas opções, sobre radios nativos (setas trocam a opção).

    <x-ui::segmented wire:model.live="view" label="Visualização">
        <x-ui::segmented.option value="lista" icon="left-to-right-list-bullet" checked>Lista</x-ui::segmented.option>
        <x-ui::segmented.option value="quadro" icon="kanban">Quadro</x-ui::segmented.option>
    </x-ui::segmented>

    size:  sm (28) · base (34, padrão)
    full:  ocupa a largura toda, com opções do mesmo tamanho
    label: nome do grupo para leitores de tela
    name:  nome dos radios para formulários comuns; sem ele, o JS gera um nome em comum
--}}
@props([
    'label' => null,
    'name' => null,
    'size' => 'base',
    'full' => false,
])

<div
    data-choice-group
    data-segmented
    role="radiogroup"
    @if ($label) aria-label="{{ $label }}" @endif
    x-data="uiChoiceGroup({ multiple: false })"
    x-modelable="value"
    x-on:change="changed()"
    {{ $attributes->class([
        'gap-0.5 border border-line bg-wrap p-[3px]',
        'flex w-full' => $full,
        'inline-flex' => ! $full,
        'rounded-[9px]' => $size === 'sm',
        'rounded-[11px]' => $size !== 'sm',
    ]) }}
>
    {{ $slot }}
</div>
