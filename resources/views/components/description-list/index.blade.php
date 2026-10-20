{{--
    Lista de dados: pares de rótulo e valor para a página de detalhes.

    <x-ui::description-list>
        <x-ui::description-list.item label="Cliente">Igreja Batista de Campo Limpo</x-ui::description-list.item>
        <x-ui::description-list.item label="Repositório" mono>devaction-labs/sge-api</x-ui::description-list.item>
    </x-ui::description-list>

    columns: 1 (padrão, rótulo ao lado) · 2 (duas colunas, rótulo em cima)
    label-width: largura da coluna de rótulos (padrão 120px)
--}}
@props([
    'columns' => 1,
    'labelWidth' => '120px',
])

<dl
    data-description-list
    style="--description-label: {{ $labelWidth }}"
    {{ $attributes->class([
        'text-sm',
        'grid gap-x-8 sm:grid-cols-2' => (int) $columns === 2,
    ]) }}
>
    {{ $slot }}
</dl>
