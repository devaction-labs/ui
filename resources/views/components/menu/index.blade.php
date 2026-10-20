{{--
    Menu de ações de um x-ui::dropdown (API no estilo Flux).

    <x-ui::dropdown>
        <x-ui::button icon:trailing="arrow-down-01">Por prazo</x-ui::button>

        <x-ui::menu>
            <x-ui::menu.radio.group wire:model.live="sort">
                <x-ui::menu.radio value="prazo" checked>Por prazo</x-ui::menu.radio>
                <x-ui::menu.radio value="valor">Por valor</x-ui::menu.radio>
            </x-ui::menu.radio.group>
            <x-ui::menu.separator />
            <x-ui::menu.item icon="download-04" kbd="⌘E">Exportar CSV</x-ui::menu.item>
            <x-ui::menu.item icon="archive-02" variant="danger">Arquivar marco</x-ui::menu.item>
        </x-ui::menu>
    </x-ui::dropdown>

    keep-open: o menu não fecha ao escolher um item
    Setas ↑ ↓, Home, End e a letra inicial movem o foco; Esc fecha e volta ao gatilho.
--}}
@aware([
    'position' => 'bottom',
    'align' => 'start',
])

@props([
    'keepOpen' => false,
])

<div
    data-menu
    data-dropdown-panel
    role="menu"
    @if ($keepOpen) data-keep-open @endif
    x-data="uiMenu"
    x-show="open"
    x-cloak
    x-on:keydown="onKeydown($event)"
    x-transition:enter="transition duration-150 ease-out motion-reduce:transition-none"
    x-transition:enter-start="opacity-0 scale-[.98]"
    x-transition:leave="transition duration-100 ease-in motion-reduce:transition-none"
    x-transition:leave-end="opacity-0"
    {{ $attributes->class([
        'absolute z-40 flex w-60 flex-col rounded-[14px] border border-line bg-card p-1.5 text-left text-ink shadow-pop',
        \DevAction\Ui\Support\DropdownPosition::classes($position, $align),
    ]) }}
>
    {{ $slot }}
</div>
