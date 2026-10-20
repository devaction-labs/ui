{{--
    Agrupa as abas e seus painéis (API no estilo Flux).

    <x-ui::tab.group>
        <x-ui::tabs wire:model="tab">
            <x-ui::tab name="geral">Visão geral</x-ui::tab>
            <x-ui::tab name="contrato">Contrato</x-ui::tab>
        </x-ui::tabs>

        <x-ui::tab.panel name="geral">…</x-ui::tab.panel>
        <x-ui::tab.panel name="contrato">…</x-ui::tab.panel>
    </x-ui::tab.group>
--}}
<div
    data-tab-group
    x-data="uiTabGroup"
    x-id="['ui-tab', 'ui-tab-panel']"
    x-on:ui-tab-change.stop="selected = $event.detail"
    {{ $attributes->class(['flex flex-col gap-4']) }}
>
    {{ $slot }}
</div>
