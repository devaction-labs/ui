{{--
    Evento da linha do tempo. Desenha o conector até o próximo item.

    <x-ui::timeline.item status="complete">…</x-ui::timeline.item>

    status: complete (indicador e conector em ink) · current (anel) · incomplete (neutro)
--}}
@aware([
    'horizontal' => false,
    'size' => 'base',
])

@props([
    'status' => null,
])

<li
    data-timeline-item
    @if ($status) data-status="{{ $status }}" @endif
    {{ $attributes->class([
        'group/timeline-item relative',
        'grid grid-cols-[auto_minmax(0,1fr)] gap-x-3' => ! $horizontal,
        'flex min-w-0 flex-1 flex-col gap-3' => $horizontal,
    ]) }}
>
    {{ $slot }}
</li>
