{{--
    Campo numérico com − e +, limites e unidade.

    <x-ui::number-input wire:model.live="hours" label="Horas no marco" min="0" max="200" step="1" unit="h" />
    <x-ui::number-input value="15" step="0.5" unit="%" size="sm" />

    min · max · step: limites e passo; ↑↓ no teclado também ajustam
    unit:             texto depois do valor (h, %, dias)
    size:             sm (36) · base (44, padrão) · lg (52)
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'unit' => null,
    'min' => null,
    'max' => null,
    'step' => 1,
    'size' => 'base',
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($value, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $attributes->get('name') ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $inputId = $attributes->get('id') ?? 'ui-number-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$unit), 0, 8));
    $isDisabled = (bool) $attributes->get('disabled');

    [$heightClasses, $buttonClasses, $valueClasses, $radiusClasses] = match ($size) {
        'sm' => ['h-9', 'w-9 text-base', 'text-sm', 'rounded-[10px]'],
        'lg' => ['h-13', 'w-13 text-xl', 'text-lg', 'rounded-[14px]'],
        default => ['h-11', 'w-11 text-lg', 'text-[17px]', 'rounded-xl'],
    };

    $jsMin = $min === null ? 'null' : (float) $min;
    $jsMax = $max === null ? 'null' : (float) $max;
@endphp

<div data-field class="flex min-w-0 flex-col gap-1.5">
    @if ($label)
        <x-ui::label :for="$inputId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-number-input
        x-data="uiNumber({ min: {{ $jsMin }}, max: {{ $jsMax }}, step: {{ (float) $step }} })"
        @class([
            'flex w-full items-stretch overflow-hidden border bg-card transition',
            $heightClasses,
            $radiusClasses,
            'border-missing ring-4 ring-missing/10' => $errorMessage,
            'border-line-strong focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $errorMessage,
            'border-dashed opacity-60' => $isDisabled,
        ])
    >
        <button
            type="button"
            x-on:click="decrement()"
            x-bind:disabled="atMin() || @js($isDisabled)"
            aria-label="Diminuir{{ $label ? ' '.$label : '' }}"
            tabindex="-1"
            class="{{ $buttonClasses }} flex shrink-0 cursor-pointer items-center justify-center border-r border-line bg-subtle text-ink-2 transition hover:bg-wrap hover:text-ink disabled:cursor-not-allowed disabled:text-line-hover"
        >−</button>

        <span class="flex min-w-0 flex-1 items-baseline justify-center gap-1.5 self-center">
            <input
                x-ref="input"
                id="{{ $inputId }}"
                inputmode="decimal"
                role="spinbutton"
                @if ($min !== null) aria-valuemin="{{ $min }}" @endif
                @if ($max !== null) aria-valuemax="{{ $max }}" @endif
                x-bind:aria-valuenow="current"
                x-on:keydown.arrow-up.prevent="increment()"
                x-on:keydown.arrow-down.prevent="decrement()"
                x-on:blur="normalize()"
                @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $inputId }}-hint" @endif
                {{ $attributes->merge(['type' => 'text', 'value' => $attributes->get('value', $min ?? 0)])->class([
                    'w-full min-w-0 bg-transparent text-center font-medium text-ink tabular-nums outline-none',
                    $valueClasses,
                    'w-[6ch] flex-none' => $unit,
                ]) }}
            />
            @if ($unit)
                <span class="shrink-0 text-[13px] text-muted">{{ $unit }}</span>
            @endif
        </span>

        <button
            type="button"
            x-on:click="increment()"
            x-bind:disabled="atMax() || @js($isDisabled)"
            aria-label="Aumentar{{ $label ? ' '.$label : '' }}"
            tabindex="-1"
            class="{{ $buttonClasses }} flex shrink-0 cursor-pointer items-center justify-center border-l border-line bg-subtle text-ink-2 transition hover:bg-wrap hover:text-ink disabled:cursor-not-allowed disabled:text-line-hover"
        >+</button>
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$inputId.'-hint'" :message="$errorMessage" />
    @endif
</div>
