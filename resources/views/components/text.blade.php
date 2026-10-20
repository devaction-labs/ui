{{--
    Texto de apoio (API no estilo Flux).

    <x-ui::text class="mt-2">Mude seus dados pessoais.</x-ui::text>
    <x-ui::text variant="strong">Total: R$ 1.290</x-ui::text>

    size:    sm · base (padrão) · lg
    variant: default · strong · subtle
--}}
@props([
    'size' => 'base',
    'variant' => 'default',
])

<p data-text {{ $attributes->class([
    'leading-normal',
    'text-[13px]' => $size === 'sm',
    'text-sm' => $size === 'base',
    'text-base' => $size === 'lg',
    'text-ink-3' => $variant === 'default',
    'font-medium text-ink' => $variant === 'strong',
    'text-muted' => $variant === 'subtle',
]) }}>{{ $slot }}</p>
