{{--
    Grupo de itens com título em mono.

    <x-ui::menu.group heading="Projeto">…</x-ui::menu.group>
--}}
@props([
    'heading' => null,
])

<div role="group" @if ($heading) aria-label="{{ $heading }}" @endif {{ $attributes->class(['flex flex-col']) }}>
    @if ($heading)
        <div aria-hidden="true" class="px-2.5 pt-2 pb-1 font-mono text-[11px] tracking-[.08em] text-muted uppercase">{{ $heading }}</div>
    @endif
    {{ $slot }}
</div>
