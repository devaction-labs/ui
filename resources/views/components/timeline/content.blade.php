{{--
    Conteúdo do evento, ao lado (ou abaixo, no horizontal) do indicador.

    <x-ui::timeline.content heading="PR #142 aberto" meta="carlos-andre · 09:12" />
    <x-ui::timeline.content>Conteúdo livre: callouts, avatares, links…</x-ui::timeline.content>
--}}
@aware([
    'horizontal' => false,
    'size' => 'base',
])

@props([
    'heading' => null,
    'meta' => null,
])

<div
    data-timeline-content
    {{ $attributes->class([
        'flex min-w-0 flex-col gap-0.5 text-sm',
        'pb-4 group-last/timeline-item:pb-0' => ! $horizontal,
        'pr-4' => $horizontal,
        'pt-0.5' => ! $horizontal && $size !== 'lg',
        'pt-1.5' => ! $horizontal && $size === 'lg',
    ]) }}
>
    @if ($heading)
        <span class="font-medium text-ink">{{ $heading }}</span>
    @endif
    @if ($meta)
        <span class="text-xs text-muted">{{ $meta }}</span>
    @endif
    {{ $slot }}
</div>
