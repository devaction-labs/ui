{{--
    Painel de detalhes ao lado do conteúdo (issue, card, contrato): seções com pares de rótulo e valor.

    <x-ui::properties>
        <x-ui::properties.section heading="Responsáveis">
            <x-slot:action><x-ui::button size="xs" variant="ghost" icon="settings-02" aria-label="Editar responsáveis" /></x-slot:action>
            …avatares…
        </x-ui::properties.section>
        <x-ui::properties.section heading="Quadro">
            <x-ui::properties.item label="Coluna">Em andamento</x-ui::properties.item>
            <x-ui::properties.item label="Estimativa" mono>5 pts</x-ui::properties.item>
        </x-ui::properties.section>
    </x-ui::properties>

    variant: plain (padrão, seções divididas por linha) · card (cada seção num cartão)
--}}
@props([
    'label' => null,
    'variant' => 'plain',
])

<aside
    data-properties
    aria-label="{{ $label ?? __('Details') }}"
    {{ $attributes->class([
        'flex min-w-0 flex-col text-sm',
        'gap-3' => $variant === 'card',
        '[&>[data-properties-section]]:border-b [&>[data-properties-section]]:border-line-soft [&>[data-properties-section]:first-child]:pt-0 [&>[data-properties-section]:last-child]:border-b-0' => $variant !== 'card',
        '[&>[data-properties-section]]:rounded-xl [&>[data-properties-section]]:border [&>[data-properties-section]]:border-line [&>[data-properties-section]]:bg-card [&>[data-properties-section]]:px-4' => $variant === 'card',
    ]) }}
>
    {{ $slot }}
</aside>
