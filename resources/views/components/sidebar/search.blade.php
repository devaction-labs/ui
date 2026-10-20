{{--
    Botão de busca da sidebar, normalmente abre a paleta de comandos.

    <x-ui::sidebar.search x-on:click="$modal('comandos').show()" />
    <x-ui::sidebar.search placeholder="Buscar contatos" kbd="/" />
--}}
@props([
    'placeholder' => null,
    'kbd' => '⌘K',
])

@php
    $placeholder ??= __('Search');
@endphp

<button
    type="button"
    data-sidebar-search
    aria-label="{{ $placeholder }}"
    {{ $attributes->class([
        'flex h-10 w-full shrink-0 cursor-pointer items-center gap-2 rounded-[10px] border border-line bg-card px-2.5 text-[13px] text-muted transition hover:border-line-hover',
        'in-data-collapsed:w-10 in-data-collapsed:justify-center in-data-collapsed:self-center in-data-collapsed:px-0',
    ]) }}
>
    <x-ui::icon name="search-01" class="size-4 shrink-0" />
    <span class="in-data-collapsed:hidden">{{ $placeholder }}</span>
    @if ($kbd)
        <kbd class="ml-auto rounded-[5px] border border-line px-[5px] py-px font-mono text-[10px] in-data-collapsed:hidden">{{ $kbd }}</kbd>
    @endif
</button>
