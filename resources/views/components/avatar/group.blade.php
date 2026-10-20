{{--
    Avatares sobrepostos. O anel usa a cor do card; para outro fundo, troque com *:ring-*.

    <x-ui::avatar.group>
        <x-ui::avatar name="Alex Nogueira" color="auto" size="sm" circle />
        <x-ui::avatar name="Carlos André" color="auto" size="sm" circle />
        <x-ui::avatar size="sm" circle>+2</x-ui::avatar>
    </x-ui::avatar.group>
--}}
<div
    data-avatar-group
    {{ $attributes->class(['flex items-center -space-x-2 *:ring-2 *:ring-card']) }}
>
    {{ $slot }}
</div>
