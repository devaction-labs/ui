{{--
    Slider (API no estilo Flux): valor único, com marcas, ou faixa com distância mínima.

    <x-ui::slider wire:model.live="coverage" label="Cobertura mínima de testes" unit="%" bubble />
    <x-ui::slider label="Rigor do review" min="0" max="3" :marks="['Leve', 'Padrão', 'Rígido', 'Máximo']" />
    <x-ui::slider wire:model="budget" label="Valor do contrato" range min="0" max="100" step="5" min-distance="10" prefix="R$ " unit=" mil" />

    range:        dois botões; wire:model recebe [mínimo, máximo]
    min-distance: distância mínima entre os botões da faixa
    marks:        rótulos das marcas (um por passo); as setas andam de marca em marca
    bubble:       bolha com o valor em cima do botão
    prefix/unit:  texto antes e depois do valor mostrado
--}}
@props([
    'label' => null,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'value' => null,
    'range' => false,
    'minDistance' => 0,
    'marks' => [],
    'prefix' => '',
    'unit' => '',
    'bubble' => false,
    'disabled' => false,
])

@php
    $min = (float) $min;
    $max = (float) $max;
    $marks = array_values($marks);
    $step = $marks !== [] ? ($max - $min) / max(count($marks) - 1, 1) : (float) $step;
    $value ??= $range ? [$min, $max] : $min;
    $labelId = 'ui-slider-'.substr(md5(($label ?? '').$min.$max), 0, 8);
    $format = fn (string $expression): string => "'".addslashes($prefix)."' + ".$expression." + '".addslashes($unit)."'";
    $markLabel = $marks !== [] ? 'marks[Math.round((low() - '.$min.') / '.$step.')]' : null;
@endphp

<div
    data-slider
    x-data="uiSlider({ min: {{ $min }}, max: {{ $max }}, range: @js((bool) $range), minDistance: {{ (float) $minDistance }}, value: @js($value) })"
    x-modelable="value"
    @if ($marks !== []) x-init="marks = @js($marks)" @endif
    {{ $attributes->class(['flex w-full flex-col gap-2.5']) }}
>
    @if ($label)
        <div class="flex items-baseline justify-between gap-3 text-sm font-medium text-ink">
            <span id="{{ $labelId }}">{{ $label }}</span>
            @if ($range)
                <span class="font-mono text-[13px] font-normal text-ink-3" x-text="{{ $format('low()') }} + ' – ' + {{ $format('high()') }}"></span>
            @elseif ($markLabel)
                <span class="text-[13px] font-normal text-ink-3" x-text="{{ $markLabel }}"></span>
            @else
                <span class="font-mono text-[13px] font-normal text-ink-3" x-text="{{ $format('low()') }}"></span>
            @endif
        </div>
    @endif

    @if ($range)
        <div class="ui-range-dual relative h-6 rounded-full" x-bind:style="fillStyle()" role="group" @if ($label) aria-labelledby="{{ $labelId }}" @endif>
            <input
                type="range"
                class="ui-range"
                min="{{ $min }}"
                max="{{ $max }}"
                step="{{ $step }}"
                aria-label="{{ __('Minimum') }}"
                x-bind:value="low()"
                x-on:input="$el.value = setLow($el.value)"
                x-bind:aria-valuetext="{{ $format('low()') }}"
                @disabled($disabled)
            />
            <input
                type="range"
                class="ui-range"
                min="{{ $min }}"
                max="{{ $max }}"
                step="{{ $step }}"
                aria-label="{{ __('Maximum') }}"
                x-bind:value="high()"
                x-on:input="$el.value = setHigh($el.value)"
                x-bind:aria-valuetext="{{ $format('high()') }}"
                @disabled($disabled)
            />
        </div>
    @else
        <div class="relative">
            @if ($bubble)
                <span
                    aria-hidden="true"
                    class="pointer-events-none absolute bottom-[calc(100%+6px)] -translate-x-1/2 rounded-[7px] bg-ink px-[7px] py-[3px] font-mono text-[11px] text-on-ink"
                    x-bind:style="{ left: `calc(${percent(low())}% + ${(0.5 - percent(low()) / 100) * 20}px)` }"
                    x-text="low()"
                ></span>
            @endif
            <input
                type="range"
                class="ui-range"
                min="{{ $min }}"
                max="{{ $max }}"
                step="{{ $step }}"
                @if ($label) aria-labelledby="{{ $labelId }}" @endif
                x-bind:value="low()"
                x-bind:style="fillStyle()"
                x-on:input="setLow($el.value)"
                x-bind:aria-valuetext="{{ $markLabel ?? $format('low()') }}"
                @disabled($disabled)
            />
        </div>
    @endif

    @if ($marks !== [])
        <div class="grid text-xs text-muted" style="grid-template-columns: repeat({{ count($marks) }}, minmax(0, 1fr))">
            @foreach ($marks as $index => $mark)
                <span @class([
                    'text-left' => $loop->first,
                    'text-right' => $loop->last,
                    'text-center' => ! $loop->first && ! $loop->last,
                ]) x-bind:class="Math.round((low() - {{ $min }}) / {{ $step }}) === {{ $index }} ? 'font-medium text-ink' : ''">{{ $mark }}</span>
            @endforeach
        </div>
    @endif
</div>
