{{--
    Abre o modal de mesmo name ao clicar no conteúdo.

    <x-ui::modal.trigger name="nova-tarefa">
        <x-ui::button icon="add-01">Nova tarefa</x-ui::button>
    </x-ui::modal.trigger>

    <x-ui::modal.trigger name="busca" shortcut="cmd.k">…</x-ui::modal.trigger>   atalho de teclado (modificadores do Alpine)
--}}
@props([
    'name',
    'shortcut' => null,
])

<div
    data-modal-trigger
    x-data
    x-on:click="$modal(@js($name)).show()"
    @if ($shortcut) x-on:keydown.{{ $shortcut }}.window.prevent="$modal(@js($name)).show()" @endif
    {{ $attributes->class(['contents']) }}
>
    {{ $slot }}
</div>
