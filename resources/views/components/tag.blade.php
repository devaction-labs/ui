{{--
    Tag de status: borda, fundo e texto. Geist Mono 12px, maiúsculas.
    Nunca só pela cor: sempre com texto ou ícone.

    <x-ui::tag color="risk">Em risco</x-ui::tag>
    <x-ui::tag color="warn" icon="sparkles">IA revisou</x-ui::tag>
    <x-ui::tag color="risk" icon="git-pull-request" :uppercase="false">#142</x-ui::tag>

    color: plain (padrão) · risk · warn · ok · info
--}}
@props([
    'color' => 'plain',
    'icon' => null,
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
    'inline-flex items-center gap-1.5 rounded-md border px-[9px] py-1 font-mono text-xs leading-none',
    'tracking-[.06em] uppercase' => $uppercase,
    $colorClasses,
]) }}>
    @if ($icon)
        <x-ui::icon :name="$icon" class="size-3.5" />
    @endif
    {{ $slot }}
</span>
