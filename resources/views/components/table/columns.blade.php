{{--
    Cabeçalho da tabela.

    <x-ui::table.columns>…colunas…</x-ui::table.columns>
--}}
@aware([
    'variant' => 'default',
    'density' => 'default',
    'selectable' => false,
    'expandable' => false,
    'stack' => false,
])

<thead @class(['max-sm:hidden' => $stack])>
    <tr {{ $attributes->class([
        'bg-subtle' => $variant !== 'minimal',
    ]) }}>
        @if ($selectable)
            <th scope="col" @class([
                'w-11 border-b border-line px-0',
                'h-11' => $density !== 'compact',
                'h-[34px]' => $density === 'compact',
            ])>
                <span class="flex justify-center">
                    <input
                        type="checkbox"
                        aria-label="Selecionar todas"
                        x-bind:checked="allState() === 'true'"
                        x-effect="$el.indeterminate = allState() === 'mixed'"
                        x-on:change="toggleAll()"
                        class="size-4 cursor-pointer rounded-[5px] accent-ink"
                    />
                </span>
            </th>
        @endif
        @if ($expandable)
            <th scope="col" class="w-11 border-b border-line"><span class="sr-only">Detalhes</span></th>
        @endif
        {{ $slot }}
    </tr>
</thead>
