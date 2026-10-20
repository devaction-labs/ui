{{--
    Switch (API no estilo Flux) sobre checkbox nativo com role="switch". Para o que vale na hora,
    sem botão de salvar; escolhas que só valem depois de salvar usam checkbox.

    <x-ui::switch wire:model.live="autoReview" label="Review automático" description="A IA revisa todo PR aberto." />
    <x-ui::switch color="ai" size="sm" aria-label="Monitorar sge-api" checked />
    <x-ui::switch label="Tema escuro" align="left" />

    size:    sm (32×20) · md (40×24, padrão) · lg (48×28)
    color:   default (ink) · ai (âmbar) · ok (verde)
    align:   right (padrão, switch à direita do texto) · left
    loading: mostra o spinner na bolinha enquanto salva
--}}
@props([
    'label' => null,
    'description' => null,
    'size' => 'md',
    'color' => 'default',
    'align' => 'right',
    'loading' => false,
])

@php
    $isDisabled = (bool) $attributes->get('disabled') || $loading;

    [$trackClasses, $knobClasses, $translateClasses] = match ($size) {
        'sm' => ['h-5 w-8', 'size-3.5', 'peer-checked:translate-x-3'],
        'lg' => ['h-7 w-12', 'size-[22px]', 'peer-checked:translate-x-5'],
        default => ['h-6 w-10', 'size-[18px]', 'peer-checked:translate-x-4'],
    };

    $checkedClasses = match ($color) {
        'ai' => 'peer-checked:bg-ai',
        'ok' => 'peer-checked:bg-ok-fg',
        default => 'peer-checked:bg-primary',
    };

    $hasText = $label || $description;
@endphp

<label @class([
    'group/switch inline-flex cursor-pointer gap-3',
    'w-full items-start justify-between' => $hasText,
    'items-center' => ! $hasText,
    'cursor-not-allowed opacity-50' => $isDisabled,
])>
    @if ($hasText && $align === 'right')
        <span class="flex min-w-0 flex-col gap-0.5">
            @if ($label)
                <span class="text-sm font-medium text-ink">{{ $label }}</span>
            @endif
            @if ($description)
                <span class="text-[13px] leading-normal text-muted">{{ $description }}</span>
            @endif
        </span>
    @endif

    <span class="relative inline-flex shrink-0 items-center">
        <input
            role="switch"
            @if ($loading) aria-busy="true" @endif
            {{ $attributes->except('disabled')->merge(['type' => 'checkbox'])->class(['peer absolute inset-0 z-10 size-full cursor-pointer appearance-none rounded-full opacity-0 disabled:cursor-not-allowed']) }}
            @disabled($isDisabled)
        />
        <span aria-hidden="true" @class([
            'flex items-center rounded-full bg-line-hover p-[3px] transition-colors duration-200 motion-reduce:transition-none',
            'peer-focus-visible:ring-2 peer-focus-visible:ring-ring peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-card',
            $trackClasses,
            $checkedClasses,
        ])></span>
        <span aria-hidden="true" @class([
            'pointer-events-none absolute left-[3px] flex items-center justify-center rounded-full bg-white shadow-sm transition-transform duration-200 motion-reduce:transition-none',
            $knobClasses,
            $translateClasses,
        ])>
            @if ($loading)
                <x-ui::spinner size="xs" class="scale-75" />
            @endif
        </span>
    </span>

    @if ($hasText && $align === 'left')
        <span class="flex min-w-0 flex-1 flex-col gap-0.5">
            @if ($label)
                <span class="text-sm font-medium text-ink">{{ $label }}</span>
            @endif
            @if ($description)
                <span class="text-[13px] leading-normal text-muted">{{ $description }}</span>
            @endif
        </span>
    @endif
</label>
