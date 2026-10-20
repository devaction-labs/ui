{{--
    Date picker (API no estilo Flux): campo que abre um calendário, data única ou faixa.

    <x-ui::date-picker wire:model.live="dueDate" label="Vencimento" min="2026-10-01" />
    <x-ui::date-picker wire:model="period" label="Período do marco" range />
    <x-ui::date-picker inline value="2026-10-16" />

    value:   '2026-10-16'; com range, ['start' => '2026-10-13', 'end' => '2026-10-24']
    min/max: datas ISO fora do intervalo ficam desativadas
    inline:  só o calendário, sem campo
    presets: atalhos (Hoje, Amanhã, Sexta, +7 dias; na faixa, últimos e próximos dias)
    name:    input escondido (name; na faixa name[start] e name[end])
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => null,
    'value' => null,
    'range' => false,
    'min' => null,
    'max' => null,
    'inline' => false,
    'presets' => true,
    'relative' => true,
    'name' => null,
    'locale' => 'pt-BR',
    'today' => null,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $pickerId = $attributes->get('id') ?? 'ui-date-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.($range ? 'range' : 'single')), 0, 8));
    $placeholder ??= $range ? 'Início → fim' : 'Escolha uma data';
    $startValue = $range ? ($value['start'] ?? null) : $value;
    $endValue = $range ? ($value['end'] ?? null) : null;
    $alpineConfig = [
        'value' => $value,
        'range' => (bool) $range,
        'min' => $min,
        'max' => $max,
        'locale' => $locale,
        'today' => $today,
    ];
@endphp

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label && ! $inline)
        <x-ui::label :for="$pickerId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-date-picker
        x-data="uiDatePicker(@js($alpineConfig))"
        x-modelable="value"
        @unless ($inline)
            x-on:click.outside="close()"
            x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); close(true) }"
        @endunless
        {{ $attributes->except(['id', 'class'])->class(['relative w-full']) }}
    >
        @if ($name)
            @if ($range)
                <input type="hidden" name="{{ $name }}[start]" value="{{ $startValue }}" data-date-raw="start" />
                <input type="hidden" name="{{ $name }}[end]" value="{{ $endValue }}" data-date-raw="end" />
            @else
                <input type="hidden" name="{{ $name }}" value="{{ $startValue }}" data-date-raw="start" />
            @endif
        @endif

        @if ($inline)
            <div class="w-fit rounded-[14px] border border-line bg-card p-3.5">
                <x-ui::date-picker.calendar :range="$range" :presets="$presets" />
            </div>
        @else
            <button
                type="button"
                id="{{ $pickerId }}"
                x-ref="trigger"
                x-on:click="toggle()"
                aria-haspopup="dialog"
                x-bind:aria-expanded="open"
                @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $pickerId }}-hint" @endif
                @class([
                    'flex h-11 w-full cursor-pointer items-center gap-2.5 rounded-xl border bg-card px-3.5 text-left text-[15px] text-ink transition outline-none',
                    'border-missing ring-4 ring-missing/10' => $errorMessage,
                    'border-line-strong hover:border-line-hover focus-visible:border-ink focus-visible:ring-4 focus-visible:ring-ink/10' => ! $errorMessage,
                ])
                x-bind:class="open && 'border-ink ring-4 ring-ink/10'"
            >
                <x-ui::icon name="calendar-03" class="size-4 shrink-0 text-ink-3" />
                <span @class(['min-w-0 flex-1 truncate first-letter:uppercase', 'font-mono text-sm' => $range]) x-show="start()" x-text="display()"></span>
                <span class="min-w-0 flex-1 truncate text-muted" x-show="! start()">{{ $placeholder }}</span>
                @if ($relative && ! $range)
                    <span class="shrink-0 text-[13px] text-muted" x-show="start()" x-text="relative(start())"></span>
                @endif
            </button>

            <div
                role="dialog"
                aria-label="{{ $label ?? 'Escolher data' }}"
                x-show="open"
                x-ui-float="{ reference: $refs.trigger }"
                x-cloak
                x-transition.opacity.duration.100ms
                class="absolute top-full left-0 z-50 mt-1.5 rounded-[14px] border border-line bg-card p-3.5 shadow-[0_16px_40px_rgb(24_24_27/0.10)]"
            >
                <x-ui::date-picker.calendar :range="$range" :presets="$presets" class="w-[19rem]" />
            </div>
        @endif
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$pickerId.'-hint'" :message="$errorMessage" />
    @endif
</div>
