{{--
    Dropdown (API no estilo Flux): contêiner que abre um x-ui::popover (ou um menu) a partir de um gatilho.

    <x-ui::dropdown>
        <x-ui::button icon:trailing="arrow-down-01">Opções</x-ui::button>

        <x-ui::popover class="w-80">…</x-ui::popover>
    </x-ui::dropdown>

    position: bottom (padrão) · top · right · left
    align:    start (padrão) · center · end
    gap:      distância em px até o gatilho (padrão 6)
    offset:   deslocamento em px no eixo do alinhamento (start e end)
    hover:    abre ao passar o mouse
    wire:model liga o aberto/fechado a uma propriedade do Livewire.
--}}
@props([
    'position' => 'bottom',
    'align' => 'start',
    'gap' => 6,
    'offset' => 0,
    'hover' => false,
])

<div
    data-dropdown
    x-data="uiDropdown({ hover: @js((bool) $hover) })"
    x-modelable="open"
    x-on:click="clicked($event)"
    x-on:click.outside="close()"
    x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); close(true) }"
    x-on:mouseenter="enter()"
    x-on:mouseleave="leave()"
    style="--dropdown-gap: {{ (int) $gap }}px; --dropdown-offset: {{ (int) $offset }}px"
    {{ $attributes->class(['relative inline-flex']) }}
>
    {{ $slot }}
</div>
