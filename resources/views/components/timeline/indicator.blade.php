{{--
    Indicador do evento, com o conector até o próximo item.
    Vazio vira um ponto de 10 px; com ícone ou número vira um círculo.

    <x-ui::timeline.indicator color="amber" />
    <x-ui::timeline.indicator><x-ui::icon name="git-pull-request" class="size-3.5" /></x-ui::timeline.indicator>
    <x-ui::timeline.indicator>2</x-ui::timeline.indicator>
    <x-ui::timeline.indicator variant="bare"><x-ui::icon name="file-01" class="size-6 text-muted" /></x-ui::timeline.indicator>

    color:   red · amber · green · blue · indigo · zinc — nomes do Tailwind mapeados para os tokens
    status:  sobrescreve o status do item só no indicador
    variant: bare (sem fundo nem tamanho fixo)
--}}
@aware([
    'horizontal' => false,
    'size' => 'base',
    'status' => null,
])

@props([
    'color' => null,
    'variant' => null,
])

@php
    $attributes = $attributes->except('status');
    $isDot = $slot->isEmpty() && $variant !== 'bare';
    $isLarge = $size === 'lg';

    $solidClasses = match ($color) {
        'red', 'rose', 'pink' => 'bg-missing text-card',
        'amber', 'orange', 'yellow' => 'bg-ai text-card',
        'green', 'emerald', 'lime', 'teal' => 'bg-ok-fg text-card',
        'blue', 'sky', 'cyan' => 'bg-learning text-card',
        'indigo', 'violet', 'purple', 'fuchsia' => 'bg-violet text-white',
        'zinc', 'gray' => 'bg-muted text-card',
        default => null,
    };

    $statusClasses = match ($status) {
        'complete' => 'bg-primary text-on-primary',
        'current' => 'bg-card text-ink ring-2 ring-ink ring-offset-2 ring-offset-card',
        'incomplete' => 'border border-line-strong bg-card text-muted',
        default => null,
    };

    $toneClasses = $solidClasses ?? $statusClasses ?? ($isDot ? 'bg-line-hover' : 'border border-line bg-wrap text-ink-3');

    $connectorClasses = $status === 'complete' ? 'bg-primary' : 'bg-line';
@endphp

<div
    data-timeline-indicator
    @class([
        'flex items-center',
        'flex-col self-stretch' => ! $horizontal,
        'w-full' => $horizontal,
    ])
>
    <span @class([
        'flex shrink-0 items-center justify-center',
        $isLarge ? 'min-h-8' : 'min-h-6',
    ])>
        <span {{ $attributes->class([
            'flex shrink-0 items-center justify-center rounded-full font-mono font-medium',
            $toneClasses => $variant !== 'bare',
            'size-2.5' => $isDot,
            'size-6 text-[11px]' => ! $isDot && $variant !== 'bare' && ! $isLarge,
            'size-8 text-xs' => ! $isDot && $variant !== 'bare' && $isLarge,
        ]) }}>{{ $slot }}</span>
    </span>

    <span aria-hidden="true" @class([
        'group-last/timeline-item:hidden',
        $connectorClasses,
        'my-1 w-px flex-1' => ! $horizontal,
        'mx-2 h-px flex-1' => $horizontal,
    ])></span>
</div>
