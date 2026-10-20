{{--
    Dois painéis com divisória arrastável: diff e resumo, lista e detalhe.

    <x-ui::resizable default="58" min="30" max="75" persist="pr-review" class="h-[300px]">
        <x-ui::resizable.panel>Diff</x-ui::resizable.panel>
        <x-ui::resizable.handle label="Tamanho do diff" />
        <x-ui::resizable.panel class="bg-ai-soft">Resumo da IA</x-ui::resizable.panel>
    </x-ui::resizable>

    default: tamanho inicial do primeiro painel em % (duplo clique na divisória volta a ele)
    min/max: limites em % · vertical: um em cima do outro
    persist: chave para lembrar o tamanho no navegador
--}}
@props([
    'default' => 50,
    'min' => 20,
    'max' => 80,
    'vertical' => false,
    'persist' => null,
])

<div
    data-resizable
    x-data="uiResizable({ size: {{ (float) $default }}, min: {{ (float) $min }}, max: {{ (float) $max }}, vertical: @js((bool) $vertical), persist: @js($persist) })"
    x-bind:style="'--resizable-size: ' + size + '%'"
    style="--resizable-size: {{ (float) $default }}%"
    {{ $attributes->class(['flex overflow-hidden rounded-xl border border-line bg-card', 'flex-col' => $vertical]) }}
>
    {{ $slot }}
</div>
