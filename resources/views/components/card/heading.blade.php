{{--
    Título do card.

    <x-ui::card.heading>Senha</x-ui::card.heading>
    <x-ui::card.heading size="lg">Segurança</x-ui::card.heading>

    size:  sm · base (padrão, 18 px) · lg (20 px)
    level: nível do título (padrão 2)
--}}
@props([
    'size' => 'base',
    'level' => 2,
])

<h{{ (int) $level }} {{ $attributes->class([
    'min-w-0 flex-1 font-semibold tracking-[-0.01em] text-ink',
    'text-[15px]' => $size === 'sm',
    'text-lg' => $size === 'base',
    'text-xl' => $size === 'lg',
]) }}>{{ $slot }}</h{{ (int) $level }}>
