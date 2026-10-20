{{--
    Lista de abas (API no estilo Flux). Funciona sozinha (alternar visões) ou dentro de tab.group.

    <x-ui::tabs>                         linha sob a aba ativa (padrão)
    <x-ui::tabs variant="segmented">     caixa com a aba ativa em destaque
    <x-ui::tabs variant="pills" size="sm">
    <x-ui::tabs wire:model.live="view">  a aba ativa vira uma propriedade do Livewire
    <x-ui::tabs scrollable>              rola na horizontal no celular

    variant: default · segmented · pills
    size:    base (padrão) · sm
--}}
@props([
    'variant' => 'default',
    'size' => 'base',
    'scrollable' => false,
])

<div
    data-tabs
    role="tablist"
    x-data="uiTabs"
    x-modelable="selected"
    x-on:keydown="moveFocus($event)"
    {{ $attributes->class([
        'flex max-w-full',
        'gap-1 border-b border-line' => $variant === 'default',
        'w-fit gap-0.5 rounded-xl border border-line bg-wrap p-1' => $variant === 'segmented',
        'gap-1' => $variant === 'pills',
        'overflow-x-auto [scrollbar-width:thin]' => $scrollable,
    ]) }}
>
    {{ $slot }}
</div>
