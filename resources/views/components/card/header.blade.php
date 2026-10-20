{{--
    Cabeçalho do card: título e subtítulo à esquerda, card.actions à direita.
    Também funciona fora do card, como título de seção acima dele.
--}}
<div {{ $attributes->class(['flex flex-wrap items-start gap-x-3 gap-y-1']) }}>
    {{ $slot }}
</div>
