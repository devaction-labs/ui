{{--
    Badge de status em texto corrido, com ponto opcional.
    Para rótulo técnico em mono maiúsculo, use <x-ui::tag>.

    <x-ui::badge color="ok" dot>Em dia</x-ui::badge>
    <x-ui::badge color="warn" dot>Atenção</x-ui::badge>
    <x-ui::badge icon="git-pull-request">PR #214</x-ui::badge>

    color: plain (padrão) · risk · warn · ok · info
    size:  sm · base (padrão)
--}}
@props([
    'color' => 'plain',
    'size' => 'base',
    'dot' => false,
    'icon' => null,
])

@php
    $colorClasses = match ($color) {
        'risk' => 'border-risk-line bg-risk-bg text-risk-fg',
        'warn' => 'border-warn-line bg-warn-bg text-warn-fg',
        'ok' => 'border-ok-line bg-ok-bg text-ok-fg',
        'info' => 'border-info-line bg-info-bg text-info-fg',
        default => 'border-plain-line bg-plain-bg text-plain-fg',
    };
@endphp

<span {{ $attributes->class([
    'inline-flex shrink-0 items-center gap-1.5 border font-medium whitespace-nowrap',
    $size === 'sm' ? 'h-5.5 rounded-md px-2 text-[11px]' : 'h-6.5 rounded-[7px] px-2.5 text-xs',
    $colorClasses,
]) }}>
    @if ($dot)
        <span class="size-1.5 rounded-full bg-current" aria-hidden="true"></span>
    @endif
    @if ($icon)
        <x-ui::icon :name="$icon" class="size-3.5" />
    @endif
    {{ $slot }}
</span>
