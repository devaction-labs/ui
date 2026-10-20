{{--
    Item do acordeão. Com heading, o slot vira o conteúdo; sem heading, use
    <x-ui::accordion.heading> e <x-ui::accordion.content> dentro dele.

    <x-ui::accordion.item heading="M3 · Financeiro" expanded>…</x-ui::accordion.item>
    <x-ui::accordion.item heading="PRs privados de clientes" disabled>…</x-ui::accordion.item>

    O slot trailing fica ao lado do cabeçalho, fora do botão: tags e ações.
--}}
@aware([
    'variant' => 'default',
])

@props([
    'heading' => null,
    'expanded' => false,
    'disabled' => false,
    'trailing' => null,
])

<div
    x-data="uiAccordionItem({ expanded: @js((bool) $expanded), disabled: @js((bool) $disabled) })"
    data-open="{{ $expanded ? 'true' : 'false' }}"
    x-bind:data-open="open.toString()"
    {{ $attributes->class([
        'group/item',
        'border-line-soft not-first:border-t' => in_array($variant, ['default', 'reverse']),
        'border-line not-first:border-t' => $variant === 'group',
        'overflow-hidden rounded-[14px] border border-line bg-card transition-colors data-[open=true]:border-line-hover' => $variant === 'cards',
    ]) }}
>
    @if ($heading !== null)
        <x-ui::accordion.heading :trailing="$trailing">{{ $heading }}</x-ui::accordion.heading>
        <x-ui::accordion.content>{{ $slot }}</x-ui::accordion.content>
    @else
        {{ $slot }}
    @endif
</div>
