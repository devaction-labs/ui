{{--
    Intervalo de datas: dois meses lado a lado, atalhos e horário opcional. A escolha só vale ao Aplicar.

    <x-ui::date-range wire:model="period" label="Período" />
    <x-ui::date-range wire:model="entry" with-time time-heading="Lançamento no banco de horas" />

    value:      ['start' => '2026-10-01', 'end' => '2026-10-15'] (+ 'start_time' e 'end_time' com with-time)
    presets:    atalhos extras: [['label' => 'Marco M3', 'start' => '2026-10-01', 'end' => '2026-10-31']]
    with-time:  início e fim do horário, em passos de step minutos (padrão 15)
    min/max:    datas ISO fora do intervalo ficam desativadas
    inline:     painel sempre aberto, sem campo; cada escolha já aplica
    name:       inputs escondidos name[start], name[end] (e name[start_time], name[end_time])
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => null,
    'value' => null,
    'min' => null,
    'max' => null,
    'presets' => [],
    'withTime' => false,
    'step' => 15,
    'timeHeading' => null,
    'timezone' => null,
    'inline' => false,
    'name' => null,
    'locale' => null,
    'today' => null,
])

@php
    $placeholder ??= __('Pick a period');
    $timeHeading ??= __('Time');
    $locale ??= str_replace('_', '-', app()->getLocale());
    $modelName = collect($attributes->getAttributes())->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? ($errorBag->first($fieldName) ?: $errorBag->first($fieldName.'.*')) : null);
    $rangeId = $attributes->get('id') ?? 'ui-range-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5((string) $label), 0, 8));
    $config = [
        'value' => $value,
        'min' => $min,
        'max' => $max,
        'locale' => $locale,
        'today' => $today,
        'time' => (bool) $withTime,
        'step' => (int) $step,
        'presets' => array_values($presets),
        'inline' => (bool) $inline,
    ];
    $rawKeys = $withTime ? ['start', 'end', 'start_time', 'end_time'] : ['start', 'end'];
@endphp

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label && ! $inline)
        <x-ui::label :for="$rangeId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-date-range
        x-data="uiDateRange(@js($config))"
        x-modelable="value"
        @unless ($inline)
            x-on:click.outside="if (open) cancel()"
            x-on:keydown.escape="if (open) { $event.stopPropagation(); cancel(); $refs.trigger.focus() }"
        @endunless
        {{ $attributes->except(['id', 'class'])->class(['relative']) }}
    >
        @if ($name)
            @foreach ($rawKeys as $rawKey)
                <input type="hidden" name="{{ $name }}[{{ $rawKey }}]" data-range-raw="{{ $rawKey }}" value="{{ $value[$rawKey] ?? '' }}" />
            @endforeach
        @endif

        @unless ($inline)
            <button
                type="button"
                id="{{ $rangeId }}"
                x-ref="trigger"
                x-on:click="toggle()"
                aria-haspopup="dialog"
                x-bind:aria-expanded="open"
                @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $rangeId }}-hint" @endif
                @class([
                    'inline-flex h-[38px] max-w-full cursor-pointer items-center gap-2 rounded-[10px] border bg-card px-3 text-sm text-ink transition outline-none',
                    'border-missing ring-4 ring-missing/10' => $errorMessage,
                    'border-line-strong hover:border-line-hover focus-visible:border-ink focus-visible:ring-4 focus-visible:ring-ink/10' => ! $errorMessage,
                ])
                x-bind:class="open && 'border-ink ring-4 ring-ink/10'"
            >
                <x-ui::icon name="calendar-03" class="size-4 shrink-0 text-ink-3" />
                <span class="truncate" x-show="value.start" x-text="label()"></span>
                <span class="truncate text-muted" x-show="! value.start">{{ $placeholder }}</span>
            </button>
        @endunless

        <div
            role="dialog"
            aria-label="{{ $label ?? __('Pick a period') }}"
            @unless ($inline)
                x-show="open"
                x-ui-float="{ reference: $refs.trigger }"
                x-cloak
                x-transition.opacity.duration.100ms
            @endunless
            @class([
                'w-max max-w-[calc(100vw-1rem)] overflow-hidden rounded-2xl border border-line bg-card text-ink',
                'absolute top-full left-0 z-50 mt-1.5 shadow-[0_16px_40px_rgb(24_24_27/0.12)]' => ! $inline,
            ])
        >
            <div @class(['grid', 'md:grid-cols-[180px_auto]' => ! $withTime, 'md:grid-cols-[180px_auto] xl:grid-cols-[180px_auto_280px]' => $withTime])>
                <div class="flex gap-0.5 overflow-x-auto border-b border-line-soft p-2.5 md:flex-col md:border-r md:border-b-0" role="group" aria-label="{{ __('Shortcuts') }}">
                    <template x-for="preset in presetList()" x-bind:key="preset.key">
                        <button
                            type="button"
                            x-on:click="choosePreset(preset)"
                            x-text="preset.label"
                            x-bind:aria-pressed="(activePreset === preset.key).toString()"
                            x-bind:class="activePreset === preset.key ? 'bg-wrap font-medium text-ink' : 'text-ink-2 hover:bg-subtle'"
                            class="h-[34px] shrink-0 cursor-pointer rounded-lg px-2.5 text-left text-sm whitespace-nowrap transition"
                        ></button>
                    </template>
                </div>

                <div class="grid gap-7 px-5 py-4 sm:grid-cols-2">
                    @foreach ([0, 1] as $offset)
                        <div @class(['max-sm:hidden' => $offset === 1])>
                            <div class="flex h-[30px] items-center">
                                @if ($offset === 0)
                                    <button type="button" x-on:click="shift(-1)" aria-label="{{ __('Previous month') }}" class="flex size-7 cursor-pointer items-center justify-center rounded-lg border border-line text-ink transition hover:bg-subtle"><x-ui::icon name="arrow-left-01" class="size-3.5" /></button>
                                @endif
                                <span class="flex-1 text-center text-sm font-semibold first-letter:uppercase" x-text="monthLabel({{ $offset }})" aria-live="polite"></span>
                                @if ($offset === 1)
                                    <button type="button" x-on:click="shift(1)" aria-label="{{ __('Next month') }}" class="flex size-7 cursor-pointer items-center justify-center rounded-lg border border-line text-ink transition hover:bg-subtle"><x-ui::icon name="arrow-right-01" class="size-3.5" /></button>
                                @endif
                            </div>
                            <div role="grid" x-bind:aria-label="monthLabel({{ $offset }})" class="mt-2.5 grid grid-cols-7 gap-y-0.5 text-center text-[13px]" x-on:mouseleave="hovered = null">
                                <template x-for="(weekday, index) in weekdays()" x-bind:key="index">
                                    <span role="columnheader" class="h-7 text-[11px] leading-7 text-muted" x-text="weekday"></span>
                                </template>
                                <template x-for="cell in days({{ $offset }})" x-bind:key="cell.key">
                                    <span role="gridcell" class="flex h-[38px] items-center justify-center" x-bind:class="bandShape(cell)">
                                        <template x-if="cell.iso">
                                            <button
                                                type="button"
                                                x-text="cell.day"
                                                x-on:click="pick(cell.iso)"
                                                x-on:mouseenter="hovered = cell.iso"
                                                x-bind:disabled="isDisabled(cell.iso)"
                                                x-bind:aria-pressed="isEdge(cell.iso)"
                                                x-bind:aria-label="dayLabel(cell.iso)"
                                                x-bind:aria-current="cell.iso === today ? 'date' : null"
                                                x-bind:class="isEdge(cell.iso) ? 'bg-primary font-semibold text-on-primary' : 'text-ink hover:bg-subtle'"
                                                class="relative size-[38px] cursor-pointer rounded-[10px] transition disabled:cursor-default disabled:text-line-hover"
                                            ></button>
                                        </template>
                                    </span>
                                </template>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($withTime)
                    <div class="flex flex-col gap-3.5 border-t border-line-soft px-5 py-4 md:col-span-2 xl:col-span-1 xl:border-t-0 xl:border-l">
                        <div class="text-sm font-semibold">{{ $timeHeading }}</div>
                        <div class="grid grid-cols-2 gap-2.5">
                            @foreach (['start' => __('Start'), 'end' => __('End')] as $timeKey => $timeLabel)
                                <label class="flex flex-col gap-1.5 text-[13px] text-ink-3">
                                    {{ $timeLabel }}
                                    <button
                                        type="button"
                                        x-on:click="timeFocus = @js($timeKey); $nextTick(() => $root.querySelector('[data-time-selected]')?.scrollIntoView({ block: 'center' }))"
                                        x-bind:aria-pressed="(timeFocus === @js($timeKey)).toString()"
                                        x-bind:class="timeFocus === @js($timeKey) ? 'border-ink ring-4 ring-ink/10' : 'border-line'"
                                        class="flex h-10 cursor-pointer items-center gap-2 rounded-[10px] border bg-card px-3 font-mono text-[15px] text-ink"
                                    ><x-ui::icon name="clock-01" class="size-[15px] text-muted" /><span x-text="draft.{{ $timeKey }}_time"></span></button>
                                </label>
                            @endforeach
                        </div>
                        <div role="listbox" aria-label="{{ __('Times') }}" class="h-[252px] overflow-y-auto rounded-[10px] border border-line p-1">
                            <template x-for="clock in slots()" x-bind:key="clock">
                                <button
                                    type="button"
                                    role="option"
                                    x-on:click="setTime(clock)"
                                    x-bind:aria-selected="(draft[timeFocus + '_time'] === clock).toString()"
                                    x-bind:data-time-selected="draft[timeFocus + '_time'] === clock ? '' : null"
                                    x-bind:class="draft[timeFocus + '_time'] === clock ? 'bg-primary text-on-primary' : 'text-ink hover:bg-subtle'"
                                    class="flex h-8 w-full cursor-pointer items-center rounded-[7px] px-2.5 text-left font-mono text-[13px]"
                                ><span x-text="clock"></span><span class="ml-auto font-sans text-xs opacity-70" x-text="slotHint(clock)"></span></button>
                            </template>
                        </div>
                        <div class="flex items-center justify-between rounded-[10px] bg-subtle p-3 text-sm"><span class="text-ink-3">{{ __('Duration') }}</span><span class="font-mono font-medium" x-text="duration()"></span></div>
                        @if ($timezone)
                            <div class="text-xs text-muted">{{ __('Time zone: :zone · :step min steps', ['zone' => $timezone, 'step' => (int) $step]) }}</div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2 border-t border-line-soft px-5 py-3.5 text-[13px] text-ink-3">
                <span x-text="dayCount() ? (dayCount() === 1 ? @js(__('1 day selected')) : @js(__(':count days selected')).replace(':count', dayCount())) : @js(__('Pick the end date'))"></span>
                @unless ($inline)
                    <x-ui::button size="sm" class="ml-auto" x-on:click="cancel(); $refs.trigger.focus()">{{ __('Cancel') }}</x-ui::button>
                    <x-ui::button size="sm" variant="primary" x-on:click="apply()" x-bind:disabled="! draft.start || ! draft.end">{{ __('Apply') }}</x-ui::button>
                @endunless
            </div>
        </div>
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$rangeId.'-hint'" :message="$errorMessage" />
    @endif
</div>
