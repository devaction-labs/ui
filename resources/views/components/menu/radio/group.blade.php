{{--
    Grupo de escolha única no menu. Funciona com wire:model.

    <x-ui::menu.radio.group wire:model.live="sort">
        <x-ui::menu.radio value="prazo" checked>Por prazo</x-ui::menu.radio>
        <x-ui::menu.radio value="valor">Por valor</x-ui::menu.radio>
    </x-ui::menu.radio.group>
--}}
@props([
    'keepOpen' => false,
])

<div
    role="group"
    x-data="{ value: null }"
    x-modelable="value"
    @if ($keepOpen) data-keep-open @endif
    {{ $attributes->class(['flex flex-col']) }}
>
    {{ $slot }}
</div>
