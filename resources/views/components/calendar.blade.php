{{--
    Calendário de eventos em mês ou semana, desenhado no servidor.

    <x-ui::calendar month="2026-10" view="month" :events="[
        ['date' => '2026-10-16', 'title' => 'Entrega M3 · SGE', 'color' => 'blue'],
        ['date' => '2026-10-16', 'title' => 'Review com cliente', 'color' => 'teal', 'start' => '14:00', 'end' => '15:00'],
    ]" />

    Navegação: sem Livewire, os botões viram links (?month=2026-11&view=week).
    Com Livewire, passe month-property e view-property: os botões chamam $set nessas propriedades.
    Clicar num dia dispara calendar-day com detail.date.

    color dos eventos: blue · amber · green · red · indigo · teal · gray
    view:  month · week (semana da data selected, ou do primeiro dia do mês)
    hours: [início, fim] da visão semanal (padrão 8 a 18) · weekend: mostra sábado e domingo na semana
--}}
@props([
    'month' => null,
    'view' => 'month',
    'events' => [],
    'today' => null,
    'selected' => null,
    'hours' => [8, 18],
    'weekend' => false,
    'monthProperty' => null,
    'viewProperty' => null,
    'legend' => [],
])

@php
    $todayDate = \Illuminate\Support\Carbon::parse($today ?? now()->toDateString())->startOfDay();
    $monthStart = \Illuminate\Support\Carbon::parse(($month ?? $todayDate->format('Y-m')).'-01')->startOfMonth();
    $selectedDate = $selected ? \Illuminate\Support\Carbon::parse($selected)->startOfDay() : null;
    $calendarLocale = app()->getLocale();
    $monthName = ucfirst($monthStart->locale($calendarLocale)->translatedFormat('F Y'));

    $tones = [
        'blue' => ['bg-info-bg text-info-fg', '#2563EB'],
        'amber' => ['bg-warn-bg text-warn-fg', '#C2850F'],
        'green' => ['bg-ok-bg text-ok-fg', '#16A34A'],
        'red' => ['bg-risk-bg text-risk-fg', '#DC2626'],
        'indigo' => ['bg-indigo-bg text-indigo-fg', '#6366F1'],
        'teal' => ['bg-ok-bg text-ok-fg', '#0D9488'],
        'gray' => ['bg-plain-bg text-plain-fg', '#A1A1AA'],
    ];
    $eventsByDate = collect($events)->groupBy(fn (array $event): string => \Illuminate\Support\Carbon::parse($event['date'])->toDateString());

    $navigate = function (string $targetMonth, string $targetView) use ($monthProperty, $viewProperty): array {
        if ($monthProperty || $viewProperty) {
            $calls = array_filter([
                $monthProperty ? "\$set('{$monthProperty}', '{$targetMonth}')" : null,
                $viewProperty ? "\$set('{$viewProperty}', '{$targetView}')" : null,
            ]);

            return ['tag' => 'button', 'attributes' => 'type="button" wire:click="'.e(implode('; ', $calls)).'"'];
        }

        return ['tag' => 'a', 'attributes' => 'href="'.e(request()->fullUrlWithQuery(['month' => $targetMonth, 'view' => $targetView])).'"'];
    };

    $currentMonth = $monthStart->format('Y-m');
    $previous = $navigate($monthStart->copy()->subMonth()->format('Y-m'), $view);
    $next = $navigate($monthStart->copy()->addMonth()->format('Y-m'), $view);

    $gridStart = $monthStart->copy()->startOfWeek(\Carbon\CarbonInterface::MONDAY);
    $gridEnd = $monthStart->copy()->endOfMonth()->endOfWeek(\Carbon\CarbonInterface::SUNDAY);
    $monthDays = [];
    for ($day = $gridStart->copy(); $day->lte($gridEnd); $day->addDay()) {
        $monthDays[] = $day->copy();
    }

    $weekAnchor = ($selectedDate ?? ($todayDate->format('Y-m') === $currentMonth ? $todayDate : $monthStart))->copy()->startOfWeek(\Carbon\CarbonInterface::MONDAY);
    $weekDays = collect(range(0, $weekend ? 6 : 4))->map(fn (int $offset) => $weekAnchor->copy()->addDays($offset));
    [$startHour, $endHour] = [(int) $hours[0], (int) $hours[1]];
    $hourHeight = 56;
    $minutesOf = fn (string $time): int => ((int) substr($time, 0, 2)) * 60 + (int) substr($time, 3, 2);
@endphp

<div data-calendar data-view="{{ $view }}" {{ $attributes->class(['flex w-full min-w-0 flex-col gap-4']) }}>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <{{ $previous['tag'] }} {!! $previous['attributes'] !!} aria-label="{{ __('Previous month') }}" class="flex size-9 cursor-pointer items-center justify-center rounded-[9px] border border-line-strong bg-card text-ink transition hover:bg-subtle">
                <x-ui::icon name="arrow-left-01" class="size-4" />
            </{{ $previous['tag'] }}>
            <span class="min-w-[140px] text-center font-semibold text-ink" aria-live="polite">{{ $monthName }}</span>
            <{{ $next['tag'] }} {!! $next['attributes'] !!} aria-label="{{ __('Next month') }}" class="flex size-9 cursor-pointer items-center justify-center rounded-[9px] border border-line-strong bg-card text-ink transition hover:bg-subtle">
                <x-ui::icon name="arrow-right-01" class="size-4" />
            </{{ $next['tag'] }}>
        </div>
        <div role="radiogroup" aria-label="{{ __('View') }}" class="flex rounded-[10px] border border-line bg-wrap p-[3px]">
            @foreach (['month' => __('Month'), 'week' => __('Week')] as $viewKey => $viewLabel)
                @php
                    $link = $navigate($currentMonth, $viewKey);
                @endphp
                <{{ $link['tag'] }} {!! $link['attributes'] !!} role="radio" aria-checked="{{ $view === $viewKey ? 'true' : 'false' }}" @class([
                    'flex h-[30px] cursor-pointer items-center rounded-lg px-3 text-[13px] text-ink transition',
                    'bg-card shadow-[0_1px_2px_rgb(24_24_27/0.12)]' => $view === $viewKey,
                    'hover:text-ink-2' => $view !== $viewKey,
                ])>{{ $viewLabel }}</{{ $link['tag'] }}>
            @endforeach
        </div>
    </div>

    @if ($view === 'week')
        <div class="overflow-x-auto rounded-xl border border-line">
            <div class="grid min-w-[640px]" style="grid-template-columns: 52px repeat({{ $weekDays->count() }}, minmax(0, 1fr))">
                <span class="border-b border-line bg-subtle"></span>
                @foreach ($weekDays as $weekDay)
                    <span @class([
                        'border-b border-l border-line px-2.5 py-2 text-[13px] text-ink',
                        'bg-wrap font-semibold' => $weekDay->isSameDay($todayDate),
                        'bg-subtle' => ! $weekDay->isSameDay($todayDate),
                    ])>{{ $weekDay->locale($calendarLocale)->translatedFormat('D d') }}</span>
                @endforeach
                <div class="flex flex-col">
                    @foreach (range($startHour, $endHour - 1) as $hour)
                        <span class="pt-1 pr-2 text-right font-mono text-[11px] text-muted" style="height: {{ $hourHeight }}px">{{ str_pad((string) $hour, 2, '0', STR_PAD_LEFT) }}h</span>
                    @endforeach
                </div>
                @foreach ($weekDays as $weekDay)
                    <div
                        class="relative border-l border-line bg-[linear-gradient(var(--line-soft)_1px,transparent_1px)]"
                        style="height: {{ ($endHour - $startHour) * $hourHeight }}px; background-size: 100% {{ $hourHeight }}px"
                        x-data
                        x-on:click.self="$dispatch('calendar-day', { date: @js($weekDay->toDateString()) })"
                    >
                        @foreach ($eventsByDate->get($weekDay->toDateString(), []) as $event)
                            @if (! empty($event['start']))
                                @php
                                    $tone = $tones[$event['color'] ?? 'blue'] ?? $tones['blue'];
                                    $startMinutes = $minutesOf($event['start']);
                                    $endMinutes = ! empty($event['end']) ? $minutesOf($event['end']) : $startMinutes + 60;
                                    $top = ($startMinutes - $startHour * 60) / 60 * $hourHeight + 2;
                                    $eventHeight = max(22, ($endMinutes - $startMinutes) / 60 * $hourHeight - 4);
                                @endphp
                                <span
                                    data-calendar-event
                                    class="absolute inset-x-1 overflow-hidden rounded-lg border-l-[3px] px-2 py-1.5 text-xs leading-[1.35] {{ $tone[0] }}"
                                    style="top: {{ round($top, 1) }}px; height: {{ round($eventHeight, 1) }}px; border-left-color: {{ $tone[1] }}"
                                >
                                    <strong class="block font-semibold">{{ $event['title'] }}</strong>
                                    {{ $event['subtitle'] ?? $event['start'].(! empty($event['end']) ? '–'.$event['end'] : '') }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div role="grid" aria-label="{{ $monthName }}" class="grid grid-cols-7 overflow-hidden rounded-xl border-t border-l border-line">
            @foreach (collect($monthDays)->take(7)->map(fn ($weekdayDate) => rtrim($weekdayDate->locale($calendarLocale)->translatedFormat('D'), '.')) as $weekdayName)
                <span role="columnheader" class="border-r border-b border-line bg-subtle px-2.5 py-2 font-mono text-[11px] tracking-[0.06em] text-muted uppercase">{{ $weekdayName }}</span>
            @endforeach
            @foreach ($monthDays as $day)
                @php
                    $inMonth = $day->format('Y-m') === $currentMonth;
                    $isToday = $day->isSameDay($todayDate);
                    $isSelected = $selectedDate && $day->isSameDay($selectedDate);
                    $dayEvents = $inMonth ? $eventsByDate->get($day->toDateString(), []) : [];
                @endphp
                <button
                    type="button"
                    role="gridcell"
                    data-date="{{ $day->toDateString() }}"
                    aria-selected="{{ $isSelected ? 'true' : 'false' }}"
                    aria-label="{{ $day->locale($calendarLocale)->isoFormat('LL') }}{{ count($dayEvents) ? ', '.trans_choice(':count event|:count events', count($dayEvents)) : '' }}"
                    x-data
                    x-on:click="$dispatch('calendar-day', { date: @js($day->toDateString()) })"
                    @class([
                        'flex min-h-[92px] min-w-0 cursor-pointer flex-col gap-1 border-r border-b border-line px-2 py-1.5 text-left transition outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-inset max-sm:min-h-16',
                        'bg-subtle' => $isSelected || ! $inMonth,
                        'bg-card hover:bg-subtle/60' => $inMonth && ! $isSelected,
                    ])
                >
                    <span @class([
                        'flex h-6 min-w-6 items-center justify-center self-start rounded-full px-1 text-xs',
                        'bg-primary font-semibold text-on-primary' => $isToday,
                        'font-semibold text-ink' => $isSelected && ! $isToday,
                        'text-ink' => $inMonth && ! $isToday && ! $isSelected,
                        'text-line-hover' => ! $inMonth,
                    ])>{{ $day->day }}</span>
                    @foreach ($dayEvents as $event)
                        @php
                            $tone = $tones[$event['color'] ?? 'blue'] ?? $tones['blue'];
                        @endphp
                        <span data-calendar-event class="flex min-w-0 items-center gap-1.5 rounded-md px-1.5 py-0.5 text-[11px] leading-[1.3] {{ $tone[0] }} max-sm:size-1.5 max-sm:p-0">
                            <span class="size-1.5 shrink-0 rounded-[2px]" style="background: {{ $tone[1] }}"></span>
                            <span class="truncate max-sm:sr-only">{{ $event['title'] }}</span>
                        </span>
                    @endforeach
                </button>
            @endforeach
        </div>
    @endif

    @if ($legend !== [])
        <div class="flex flex-wrap gap-4 text-xs text-ink-3">
            @foreach ($legend as $color => $legendLabel)
                <span class="flex items-center gap-1.5">
                    <span class="size-2 rounded-[2px]" style="background: {{ ($tones[$color] ?? $tones['blue'])[1] }}"></span>{{ $legendLabel }}
                </span>
            @endforeach
        </div>
    @endif
</div>
