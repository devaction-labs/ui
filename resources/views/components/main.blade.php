{{--
    Área principal do app ao lado da sidebar: painel branco arredondado sobre o fundo do frame.

    <x-ui::main>…</x-ui::main>
    <x-ui::main container>…</x-ui::main>   conteúdo centralizado com largura máxima

    container: limita a largura do conteúdo (max-w-7xl) e centraliza
--}}
@props([
    'container' => false,
])

<main data-main {{ $attributes->class(['min-w-0 flex-1 bg-panel px-4 py-5 md:rounded-[18px] md:px-7 md:py-[22px] md:shadow-[0_1px_2px_rgb(24_24_27/0.06)]']) }}>
    @if ($container)
        <div class="mx-auto w-full max-w-7xl">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endif
</main>
