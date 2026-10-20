{{--
    Lista ordenável: arrastar pela alça, Espaço para pegar e ↑ ↓ para mover, ou os botões de subir e descer.

    <x-ui::sortable-list sort="reorderMilestones" label="Marcos do projeto">
        @foreach ($milestones as $milestone)
            <x-ui::sortable-list.item :key="$milestone->id" :label="$milestone->name" wire:key="milestone-{{ $milestone->id }}">
                {{ $milestone->name }}
            </x-ui::sortable-list.item>
        @endforeach
    </x-ui::sortable-list>

    sort:     método do Livewire chamado com (key, nova posição a partir de 0), como o wire:sort
    Sempre dispara reorder com { key, position, order } no detail (order é a lista de keys na nova ordem).
    buttons:  false esconde os botões de subir/descer (o teclado continua funcionando)
--}}
@props([
    'sort' => null,
    'label' => 'Lista ordenável',
    'buttons' => true,
])

@php
    $helpId = 'ui-sortable-'.substr(md5($label), 0, 8).'-help';
@endphp

<div data-sortable-list x-data="uiSortableList({ sort: @js($sort), buttons: @js((bool) $buttons) })" {{ $attributes->class(['flex flex-col gap-2']) }}>
    <ul role="list" aria-label="{{ $label }}" aria-describedby="{{ $helpId }}" class="flex flex-col gap-1.5" x-ref="list">
        {{ $slot }}
    </ul>
    <p id="{{ $helpId }}" class="sr-only">Para mover um item, foque a alça, aperte Espaço, use as setas e aperte Espaço de novo. Esc cancela.</p>
    <p class="min-h-[18px] text-[13px] text-ink-3" aria-live="polite" x-text="message"></p>
</div>
