{{--
    Acordeão (API no estilo Flux).

    <x-ui::accordion exclusive transition>
        <x-ui::accordion.item heading="A IA guarda meu código?" expanded>
            Não. Ela lê o diff do PR, faz o review e descarta.
        </x-ui::accordion.item>

        <x-ui::accordion.item>
            <x-ui::accordion.heading>
                Ecto <x-ui::badge size="sm" class="ml-auto">3 lições</x-ui::badge>
            </x-ui::accordion.heading>
            <x-ui::accordion.content>Preload vs. join, Ecto.Multi e changesets aninhados.</x-ui::accordion.content>
        </x-ui::accordion.item>
    </x-ui::accordion>

    variant:    default (divisórias, seta à direita) · reverse (seta à esquerda) · group (caixa com borda) · cards (um card por item)
    exclusive:  um item aberto por vez
    transition: anima abrir e fechar (x-collapse)

    Setas ↑ ↓, Home e End movem o foco entre os cabeçalhos.
--}}
@props([
    'variant' => 'default',
    'exclusive' => false,
    'transition' => false,
])

<div
    data-accordion
    x-data="uiAccordion({ exclusive: @js((bool) $exclusive) })"
    x-on:keydown="moveFocus($event)"
    {{ $attributes->class([
        'flex flex-col',
        'overflow-hidden rounded-[14px] border border-line bg-card' => $variant === 'group',
        'gap-2.5' => $variant === 'cards',
    ]) }}
>
    {{ $slot }}
</div>
