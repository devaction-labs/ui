{{--
    Corpo da tabela.

    <x-ui::table.rows>…linhas…</x-ui::table.rows>
--}}
@aware([
    'striped' => false,
    'stack' => false,
])

<tbody {{ $attributes->class([
    '[&>tr[data-table-row]:nth-of-type(even)]:bg-subtle' => $striped,
    'max-sm:flex max-sm:flex-col max-sm:gap-2.5' => $stack,
]) }}>
    {{ $slot }}
</tbody>
