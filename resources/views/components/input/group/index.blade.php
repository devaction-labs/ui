{{--
    Junta campos e complementos numa linha só, com as bordas coladas.

    <x-ui::input.group>
        <x-ui::input.group.prefix>https://</x-ui::input.group.prefix>
        <x-ui::input placeholder="devaction.com.br" />
    </x-ui::input.group>

    <x-ui::input.group>
        <x-ui::input placeholder="Valor" />
        <x-ui::select class="max-w-28"><option>BRL</option><option>USD</option></x-ui::select>
    </x-ui::input.group>
--}}
<div
    data-input-group
    {{ $attributes->class([
        'flex w-full items-stretch',
        '*:not-first:-ml-px *:not-first:rounded-l-none *:not-last:rounded-r-none',
        '*:focus-within:relative *:focus-within:z-10',
        '[&>*:not(:first-child)_select]:rounded-l-none [&>*:not(:last-child)_select]:rounded-r-none',
    ]) }}
>
    {{ $slot }}
</div>
