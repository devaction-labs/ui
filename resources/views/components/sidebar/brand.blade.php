{{--
    Marca no topo da sidebar. Sem slot, usa o logo da DevAction.

    <x-ui::sidebar.brand name="DevAction IA" href="/" />
    <x-ui::sidebar.brand name="Acme CRM" href="/"><img src="/logo.svg" class="size-9 rounded-[10px]" alt="" /></x-ui::sidebar.brand>
--}}
@props([
    'name' => null,
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class(['flex min-w-0 items-center gap-2.5 rounded-[10px] outline-none focus-visible:ring-2 focus-visible:ring-ring']) }}>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        <x-ui::logo />
    @endif
    @if ($name)
        <span class="truncate text-[17px] font-semibold whitespace-nowrap text-ink in-data-collapsed:hidden">{{ $name }}</span>
    @endif
</{{ $tag }}>
