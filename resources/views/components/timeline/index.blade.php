{{--
    Linha do tempo (API no estilo Flux): eventos de um PR, passos de um pedido, histórico de um marco.

    <x-ui::timeline>
        <x-ui::timeline.item>
            <x-ui::timeline.indicator color="amber" />
            <x-ui::timeline.content heading="IA revisou: 2 problemas" meta="09:14" />
        </x-ui::timeline.item>
    </x-ui::timeline>

    horizontal: passos lado a lado, com o conector entre eles
    size:       base · lg (indicadores de 32 px, bons para passos numerados)
--}}
@props([
    'horizontal' => false,
    'size' => 'base',
])

<ol
    data-timeline
    {{ $attributes->class([
        'm-0 list-none p-0',
        'flex flex-col' => ! $horizontal,
        'flex w-full items-start' => $horizontal,
    ]) }}
>
    {{ $slot }}
</ol>
