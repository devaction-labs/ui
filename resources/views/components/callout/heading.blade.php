{{--
    Título do callout. Com icon, o ícone fica dentro do título (layout compacto).

    <x-ui::callout.heading>Marco vence sexta</x-ui::callout.heading>
    <x-ui::callout.heading icon="news">Política atualizada</x-ui::callout.heading>
--}}
@aware([
    'stacked' => false,
])

@props([
    'icon' => null,
])

<strong {{ $attributes->class([
    'flex items-center gap-2 font-semibold',
    'text-ink' => $stacked,
]) }}>
    @if ($icon instanceof \Illuminate\View\ComponentSlot)
        {{ $icon }}
    @elseif ($icon)
        <x-ui::icon :name="$icon" class="size-4" />
    @endif
    {{ $slot }}
</strong>
