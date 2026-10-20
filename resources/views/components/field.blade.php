{{--
    Campo de formulário: rótulo, controle, descrição e erro em coluna.

    <x-ui::field>
        <x-ui::label>E-mail</x-ui::label>
        <x-ui::input type="email" wire:model="email" />
        <x-ui::error name="email" />
    </x-ui::field>

    variant: block (padrão, empilhado) · inline (rótulo ao lado, para checkbox e switch)
--}}
@props([
    'variant' => 'block',
])

<div
    data-field
    {{ $attributes->class([
        'flex min-w-0',
        'flex-col gap-1.5' => $variant !== 'inline',
        'items-start gap-3' => $variant === 'inline',
    ]) }}
>
    {{ $slot }}
</div>
