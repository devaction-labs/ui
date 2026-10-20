{{--
    Subtítulo do card. mono para a linha técnica dos painéis do design (classes, componentes).

    <x-ui::card.subheading>Alterada há 3 meses</x-ui::card.subheading>
    <x-ui::card.subheading mono>.accordion · details[name]</x-ui::card.subheading>
--}}
@props([
    'mono' => false,
])

<p {{ $attributes->class([
    'order-last basis-full leading-normal text-muted',
    'font-mono text-[11px]' => $mono,
    'text-[13px]' => ! $mono,
]) }}>{{ $slot }}</p>
