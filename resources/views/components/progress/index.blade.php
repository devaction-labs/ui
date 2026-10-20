{{--
    Barra de progresso (API no estilo Flux).

    <x-ui::progress value="69" />
    <x-ui::progress value="62" max="80" color="amber" label="Banco de horas" value-label="62 h de 80 h" description="Faltam 18 h" />
    <x-ui::progress value="2" max="5" variant="segmented" current label="Marcos" value-label="M3 de 5" />
    <x-ui::progress label="Sincronizando repositórios" size="sm" indeterminate />

    <x-ui::progress max="5" label="Parcelas" value-label="2 de 5">
        <x-ui::progress.segment value="1" color="green" />
        <x-ui::progress.segment value="1" color="amber" />
    </x-ui::progress>

    color:   padrão ink · amber (IA) · green · red · blue · indigo — nomes do Tailwind mapeados para os tokens
    size:    sm (4 px) · base (6 px, padrão) · lg (8 px)
    variant: default · segmented (uma pílula por passo; max é o número de passos)
--}}
@props([
    'value' => 0,
    'max' => 100,
    'color' => null,
    'size' => 'base',
    'variant' => 'default',
    'label' => null,
    'description' => null,
    'showValue' => false,
    'valueLabel' => null,
    'current' => false,
    'indeterminate' => false,
])

@php
    $max = max((float) $max, 1);
    $value = min(max((float) $value, 0), $max);
    $percentage = round($value / $max * 100, 2);

    $fillClasses = match ($color) {
        'amber', 'orange', 'yellow', 'ai' => 'bg-ai',
        'green', 'emerald', 'lime', 'teal' => 'bg-ok-fg',
        'red', 'rose', 'pink' => 'bg-missing',
        'blue', 'sky', 'cyan' => 'bg-learning',
        'indigo', 'violet', 'purple', 'fuchsia' => 'bg-violet',
        default => 'bg-ink',
    };

    $heightClasses = match ($size) {
        'sm' => 'h-1',
        'lg' => 'h-2',
        default => 'h-1.5',
    };

    $displayValue = $valueLabel ?? ($showValue ? rtrim(rtrim(number_format($percentage, 1, ',', ''), '0'), ',').'%' : null);
    $hasSegments = $slot->isNotEmpty();
    $hasHeader = $label || $displayValue;
    $stepCount = (int) $max;
    $completedSteps = (int) floor($value);
@endphp

<div data-progress {{ $attributes->class(['flex w-full flex-col gap-2']) }}>
    @if ($hasHeader)
        <div class="flex items-baseline justify-between gap-3 text-sm">
            @if ($label)
                <span class="font-medium text-ink">{{ $label }}</span>
            @endif
            @if ($displayValue)
                <span class="ml-auto font-mono text-xs text-ink-3 tabular-nums">{{ $displayValue }}</span>
            @endif
        </div>
    @endif

    <div
        role="progressbar"
        @if ($label) aria-label="{{ $label }}" @endif
        aria-valuemin="0"
        aria-valuemax="{{ $variant === 'segmented' ? $stepCount : $max + 0 }}"
        @unless ($indeterminate)
            aria-valuenow="{{ $variant === 'segmented' ? $completedSteps : $value + 0 }}"
            @if ($displayValue) aria-valuetext="{{ $displayValue }}" @endif
        @endunless
        style="{{ $variant === 'segmented' ? "grid-template-columns: repeat({$stepCount}, minmax(0, 1fr))" : "--progress: {$percentage}%" }}"
        @class([
            'relative w-full',
            $heightClasses,
            'grid gap-1.5' => $variant === 'segmented',
            'flex gap-0.5 overflow-hidden rounded-full bg-line' => $variant !== 'segmented',
        ])
    >
        @if ($variant === 'segmented')
            @for ($step = 1; $step <= $stepCount; $step++)
                <span @class([
                    'rounded-full',
                    $fillClasses => $step <= $completedSteps,
                    'bg-ai' => $current && $step === $completedSteps + 1,
                    'bg-line' => $step > $completedSteps && ! ($current && $step === $completedSteps + 1),
                ])></span>
            @endfor
        @elseif ($indeterminate)
            <span class="{{ $fillClasses }} absolute inset-y-0 left-0 w-2/5 animate-indeterminate rounded-full motion-reduce:w-full motion-reduce:animate-none motion-reduce:opacity-40"></span>
        @elseif ($hasSegments)
            {{ $slot }}
        @else
            <span class="{{ $fillClasses }} h-full w-(--progress) rounded-full transition-[width] duration-300 motion-reduce:transition-none"></span>
        @endif
    </div>

    @if ($description)
        <span class="text-xs text-muted">{{ $description }}</span>
    @endif
</div>
