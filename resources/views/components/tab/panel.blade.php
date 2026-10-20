{{--
    Painel de uma aba. Aparece quando a aba de mesmo name está ativa.

    <x-ui::tab.panel name="geral">…</x-ui::tab.panel>
    <x-ui::tab.panel name="geral" selected>…</x-ui::tab.panel>   visível já no primeiro carregamento
--}}
@props([
    'name',
    'selected' => false,
])

<div
    data-tab-panel
    role="tabpanel"
    tabindex="0"
    x-show="selected === @js((string) $name)"
    x-bind:id="$id('ui-tab-panel', @js((string) $name))"
    x-bind:aria-labelledby="$id('ui-tab', @js((string) $name))"
    @unless ($selected) x-cloak @endunless
    {{ $attributes->class(['outline-none focus-visible:ring-2 focus-visible:ring-ring']) }}
>
    {{ $slot }}
</div>
