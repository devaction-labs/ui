{{--
    Número de destaque com rótulo, tendência e contexto.

    <x-ui::stat label="Pronto pra entregar" value="78,4" unit="%" trend="+6 pts" description="desde segunda" />
    <x-ui::stat label="Sem PR ainda" value="17" color="risk" description="3 vencem esta semana" />
    <x-ui::stat label="Destaque" value="4" variant="inverted" description="PRs aguardando review" />
    <x-ui::stat label="componentes" value="58" size="sm" />

    variant: card (padrão) · plain (sem borda) · inverted (fundo escuro de destaque)
    size:    base (valor de 44 px, rótulo mono em cima) · sm (valor de 28 px, rótulo embaixo)
    color:   default · ai · risk · ok — cor do valor
    trend:   texto da variação; a direção vem do sinal (+ sobe, - desce) ou de trend:direction
--}}
@aware([
    'divided' => false,
])

@props([
    'label' => null,
    'value' => null,
    'unit' => null,
    'description' => null,
    'trend' => null,
    'icon' => null,
    'variant' => 'card',
    'size' => 'base',
    'color' => 'default',
])

@php
    $trendDirection = $attributes->get('trend:direction') ?? match (true) {
        $trend !== null && str_starts_with(trim((string) $trend), '+') => 'up',
        $trend !== null && preg_match('/^\s*[-−]/u', (string) $trend) === 1 => 'down',
        default => 'flat',
    };
    $trendInverse = filter_var($attributes->get('trend:inverse', false), FILTER_VALIDATE_BOOLEAN);
    $attributes = $attributes->except(['trend:direction', 'trend:inverse']);

    $trendIsGood = $trendInverse ? $trendDirection === 'down' : $trendDirection === 'up';
    $trendIsBad = $trendInverse ? $trendDirection === 'up' : $trendDirection === 'down';
    $trendColor = $trendIsGood ? 'ok' : ($trendIsBad ? 'risk' : 'plain');
    $trendIcon = match ($trendDirection) {
        'up' => 'arrow-up-01',
        'down' => 'arrow-down-01',
        default => null,
    };

    $isInverted = $variant === 'inverted';
    $isSmall = $size === 'sm';

    $valueColorClasses = $isInverted ? 'text-on-ink' : match ($color) {
        'ai' => 'text-accent',
        'risk' => 'text-risk-fg',
        'ok' => 'text-ok-fg',
        default => 'text-ink',
    };

    $mutedClasses = $isInverted ? 'text-on-ink/70' : 'text-muted';
@endphp

<div
    data-stat
    {{ $attributes->class([
        'flex min-w-0 flex-col',
        'gap-2' => ! $isSmall,
        'px-[22px] py-5' => $divided,
        'rounded-[14px] p-5' => ! $divided && $variant !== 'plain',
        'border border-line bg-card' => ! $divided && $variant === 'card',
        'bg-ink' => ! $divided && $isInverted,
    ]) }}
>
    @if ($label && $isSmall)
        <span class="order-last mt-1 text-[13px] {{ $mutedClasses }}">{{ $label }}</span>
    @elseif ($label)
        <span class="flex items-center gap-2 font-mono text-xs tracking-[.08em] uppercase {{ $mutedClasses }}">
            @if ($icon)
                <x-ui::icon :name="$icon" class="size-3.5" />
            @endif
            {{ $label }}
        </span>
    @endif

    <span @class([
        'font-medium tabular-nums',
        $valueColorClasses,
        'text-[44px] leading-none tracking-[-0.04em]' => ! $isSmall,
        'text-[28px] font-semibold tracking-[-0.02em]' => $isSmall,
    ])>{{ $value }}@if ($unit)<span @class(['ml-0.5', $mutedClasses, 'text-2xl' => ! $isSmall, 'text-lg' => $isSmall])>{{ $unit }}</span>@endif{{ $slot }}</span>

    @if ($trend || $description)
        <span class="flex flex-wrap items-center gap-2 text-[13px] {{ $mutedClasses }}">
            @if ($trend)
                <x-ui::tag :color="$trendColor" :icon="$trendIcon" size="sm">{{ $trend }}</x-ui::tag>
            @endif
            @if ($description)
                <span>{{ $description }}</span>
            @endif
        </span>
    @endif
</div>
