{{--
    Tag de status: borda, fundo e texto. Geist Mono 12px, maiúsculas.
    Nunca só pela cor: sempre com texto ou ícone.

    <x-ui::tag color="risk">Em risco</x-ui::tag>
    <x-ui::tag color="warn" icon="sparkles">IA revisou</x-ui::tag>
    <x-ui::tag color="risk" icon="git-pull-request" :uppercase="false">#142</x-ui::tag>

    color: plain (padrão) · risk · warn · ok · info
    size:  sm (11 px, para tendências e metadados) · base (12 px, padrão)
--}}
@props([
    'color' => 'plain',
    'icon' => null,
    'size' => 'base',
    'uppercase' => true,
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
    'inline-flex items-center gap-1.5 rounded-md border font-mono leading-none',
    'px-[9px] py-1 text-xs' => $size !== 'sm',
    'px-[7px] py-[3px] text-[11px]' => $size === 'sm',
    'tracking-[.06em] uppercase' => $uppercase,
    $colorClasses,
]) }}>
    @if ($icon)
        <x-ui::icon :name="$icon" :class="$size === 'sm' ? 'size-3' : 'size-3.5'" />
    @endif
    {{ $slot }}
</span>
