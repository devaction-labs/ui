{{--
    Stepper horizontal de um fluxo em passos (assistente, onboarding, checkout).

    <x-ui::stepper :current="$step" :steps="[
        ['label' => 'Cliente', 'description' => 'quem contrata'],
        ['label' => 'Escopo', 'description' => 'marcos e entregas'],
        ['label' => 'Valores', 'description' => 'parcelas'],
        ['label' => 'Revisão', 'description' => 'enviar para assinar'],
    ]" />

    current: índice do passo atual, começando em 1 (passos antes dele ficam feitos)
    error:   marca o passo atual como falho (texto vira "falhou · tente de novo")
    counter: mostra "Passo 2 de 4"
--}}
@props([
    'steps' => [],
    'current' => 1,
    'error' => false,
    'counter' => true,
    'errorText' => null,
])

@php
    $errorText ??= __('failed · try again');
    $steps = array_values($steps);
    $total = count($steps);
    $current = max(1, min((int) $current, $total + 1));
@endphp

<div data-stepper {{ $attributes->class(['flex flex-col gap-3']) }}>
    @if ($counter && $total)
        <span class="self-end font-mono text-xs text-muted">{{ __('Step :current of :total', ['current' => min($current, $total), 'total' => $total]) }}</span>
    @endif

    <ol class="grid gap-2" style="grid-template-columns: repeat({{ max($total, 1) }}, minmax(0, 1fr))">
        @foreach ($steps as $index => $step)
            @php
                $number = $index + 1;
                $state = match (true) {
                    $number < $current => 'done',
                    $number === $current && $error => 'error',
                    $number === $current => 'current',
                    default => 'todo',
                };
            @endphp
            <li data-state="{{ $state }}" @if (in_array($state, ['current', 'error'], true)) aria-current="step" @endif class="flex min-w-0 flex-col gap-2.5">
                <span @class([
                    'h-1 rounded-full',
                    'bg-ink' => $state === 'done',
                    'bg-missing' => $state === 'error',
                    'bg-ai' => $state === 'current',
                    'bg-line' => $state === 'todo',
                ])></span>
                <span class="flex min-w-0 items-center gap-2.5">
                    <span @class([
                        'flex size-7 flex-none items-center justify-center rounded-full font-mono text-xs',
                        'bg-ink text-on-ink' => $state === 'done',
                        'border-2 border-missing bg-card text-missing' => $state === 'error',
                        'border-2 border-ink bg-card text-ink' => $state === 'current',
                        'border-[1.5px] border-line-hover bg-card text-muted' => $state === 'todo',
                    ])>
                        @if ($state === 'done')
                            <x-ui::icon name="tick-02" class="size-3.5" />
                            <span class="sr-only">{{ __('Done:') }}</span>
                        @else
                            {{ $number }}
                        @endif
                    </span>
                    <span class="flex min-w-0 flex-col max-sm:sr-only">
                        <span @class([
                            'truncate text-sm',
                            'font-semibold' => $state === 'current',
                            'font-medium' => $state !== 'current',
                            'text-risk-fg' => $state === 'error',
                            'text-ink' => in_array($state, ['current', 'done'], true),
                            'text-muted' => $state === 'todo',
                        ])>{{ $step['label'] ?? '' }}</span>
                        @if ($state === 'error' || ! empty($step['description']))
                            <span class="truncate text-xs text-muted">{{ $state === 'error' ? $errorText : $step['description'] }}</span>
                        @endif
                    </span>
                </span>
            </li>
        @endforeach
    </ol>

    {{ $slot }}
</div>
