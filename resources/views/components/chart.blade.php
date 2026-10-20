{{--
    Gráficos em SVG gerados no servidor, sem dependências.

    <x-ui::chart type="line" label="Faturamento por mês" :labels="['mar', 'abr', 'mai']" format="R$ :value mil" :series="[
        ['name' => 'Realizado', 'data' => [42, 51, 48]],
        ['name' => 'Previsto', 'data' => [40, 45, 50], 'color' => '#C2850F'],
    ]" table />
    <x-ui::chart type="column" :labels="$weeks" :series="[['name' => 'PRs', 'data' => $counts]]" />
    <x-ui::chart type="bar" :labels="['SGE', 'Loja']" :series="[['name' => 'Horas', 'data' => [132, 48]]]" format=":value h" />
    <x-ui::chart type="donut" :labels="['Prontos', 'Em review']" :series="[['data' => [18, 4]]]" center="69%" center-label="pronto" />
    <x-ui::chart type="sparkline" :series="[['data' => [3, 5, 4, 8]]]" />

    type:     line · column · bar · donut · sparkline
    format:   molde do valor (":value h", "R$ :value mil"); decimals: casas decimais
    label:    descrição para leitores de tela (aria-label do SVG)
    legend:   mostra a legenda (line com 2+ séries e donut, por padrão)
    table:    botão "Ver tabela" com os mesmos números (acessível e copiável)
    colors:   cores das fatias e barras (padrão: paleta do kit)
--}}
@props([
    'type' => 'line',
    'labels' => [],
    'series' => [],
    'format' => ':value',
    'decimals' => 0,
    'label' => null,
    'legend' => null,
    'table' => false,
    'colors' => null,
    'center' => null,
    'centerLabel' => null,
    'height' => null,
])

@php
    $palette = $colors ?? \DevAction\Ui\Support\ChartGeometry::PALETTE;
    $labels = array_values($labels);
    $series = collect($series)->values()->map(fn (array $item, int $index): array => [
        'name' => $item['name'] ?? __('Series :number', ['number' => $index + 1]),
        'data' => array_map('floatval', array_values($item['data'] ?? [])),
        'color' => $item['color'] ?? $palette[$index % count($palette)],
    ])->all();
    $first = $series[0] ?? ['name' => '', 'data' => [], 'color' => $palette[0]];
    $fmt = fn (float $value): string => \DevAction\Ui\Support\ChartGeometry::format($value, $format, (int) $decimals);
    $legend ??= ($type === 'line' && count($series) > 1) || $type === 'donut';
    $ariaLabel = $label ?? collect($series)->pluck('name')->implode(', ');

    $chartHeight = (float) ($height ?? ($type === 'column' ? 220 : 280));
    $count = max(count($labels), count($first['data']), 1);

    $maximum = collect($series)->flatMap(fn (array $item): array => $item['data'])->max() ?? 0;
    $scale = \DevAction\Ui\Support\ChartGeometry::scale((float) $maximum);

    // Posições em % da área do gráfico: o SVG estica, o texto e os pontos ficam do mesmo tamanho em qualquer largura
    $slot = 100 / $count;
    $yFor = fn (float $value): float => round(100 - ($value / $scale['max']) * 100, 3);
    $xFor = $type === 'line'
        ? fn (int $index): float => $count > 1 ? round(100 * $index / ($count - 1), 3) : 50.0
        : fn (int $index): float => round($slot * ($index + 0.5), 3);

    // Valor final de cada linha, afastado no mínimo 16 px do vizinho para os rótulos não se sobreporem
    $plotHeight = max($chartHeight - 46, 1);
    $endLabels = collect($series)->map(fn (array $item, int $index): array => [
        'series' => $index,
        'value' => end($item['data']) ?: 0.0,
        'top' => $yFor(end($item['data']) ?: 0.0) / 100 * $plotHeight,
    ])->filter(fn (array $label): bool => $series[$label['series']]['data'] !== [])->sortBy('top')->values()->all();
    for ($labelIndex = 1; $labelIndex < count($endLabels); $labelIndex++) {
        $endLabels[$labelIndex]['top'] = max($endLabels[$labelIndex]['top'], $endLabels[$labelIndex - 1]['top'] + 16);
    }
    $endLabelTops = collect($endLabels)->mapWithKeys(fn (array $label): array => [$label['series'] => round($label['top'], 2)])->all();

    $tooltipRows = collect(range(0, $count - 1))->map(fn (int $index): array => [
        'title' => $labels[$index] ?? (string) ($index + 1),
        'values' => collect($series)->map(fn (array $item): array => [
            'name' => $item['name'],
            'color' => $item['color'],
            'value' => isset($item['data'][$index]) ? $fmt($item['data'][$index]) : '—',
        ])->all(),
        'left' => $xFor($index),
    ])->all();
@endphp

<div data-chart data-type="{{ $type }}" x-data="{ hover: null, showTable: false }" {{ $attributes->class(['relative w-full min-w-0']) }}>
    @if ($legend || $table)
        <div class="mb-3.5 flex flex-wrap items-center justify-end gap-4">
            @if ($legend && $type !== 'donut')
                @foreach ($series as $item)
                    <span class="flex items-center gap-1.5 text-[13px] text-ink-2">
                        <span class="h-0.5 w-3.5 rounded-sm" style="background: {{ $item['color'] }}"></span>{{ $item['name'] }}
                    </span>
                @endforeach
            @endif
            @if ($table)
                <x-ui::button size="sm" x-on:click="showTable = ! showTable" x-bind:aria-pressed="showTable">
                    <span x-text="showTable ? @js(__('Show chart')) : @js(__('Show table'))">{{ __('Show table') }}</span>
                </x-ui::button>
            @endif
        </div>
    @endif

    <div x-show="! showTable">
        @if ($type === 'line' || $type === 'column')
            <div class="relative" style="height: {{ $chartHeight }}px">
                <div @class(['absolute top-4 bottom-[30px] left-11', 'right-[76px]' => $type === 'line', 'right-5' => $type !== 'line']) x-on:mouseleave="hover = null">
                    @foreach ($scale['ticks'] as $tick)
                        <div @class(['absolute left-0 border-t border-line-soft', '-right-14' => $type === 'line', 'right-0' => $type !== 'line']) style="top: {{ $yFor($tick) }}%"></div>
                        <span class="absolute right-full mr-2 -translate-y-1/2 font-mono text-[12px] leading-none text-muted" style="top: {{ $yFor($tick) }}%">{{ \DevAction\Ui\Support\ChartGeometry::format($tick, ':value', $scale['step'] < 1 ? 1 : 0) }}</span>
                    @endforeach
                    @foreach ($labels as $index => $text)
                        <span class="absolute top-full mt-2.5 -translate-x-1/2 text-[12px] leading-none whitespace-nowrap text-muted" style="left: {{ $xFor($index) }}%" x-bind:class="hover === {{ $index }} && 'text-ink!'">{{ $text }}</span>
                    @endforeach

                    @if ($type === 'line')
                        @foreach (range(0, $count - 1) as $index)
                            <div class="absolute inset-y-0 w-px bg-line-hover" style="left: {{ $xFor($index) }}%" x-show="hover === {{ $index }}" x-cloak></div>
                        @endforeach
                        <svg viewBox="0 0 100 100" preserveAspectRatio="none" role="img" aria-label="{{ $ariaLabel }}" class="absolute inset-0 size-full overflow-visible">
                            @foreach (array_reverse($series, true) as $seriesIndex => $item)
                                @php
                                    $points = collect($item['data'])->map(fn (float $value, int $index): array => [$xFor($index), $yFor($value)])->all();
                                @endphp
                                @if ($seriesIndex === 0)
                                    <path d="{{ \DevAction\Ui\Support\ChartGeometry::areaPath($points, 100) }}" fill="{{ $item['color'] }}" fill-opacity="0.08" />
                                @endif
                                <path d="{{ \DevAction\Ui\Support\ChartGeometry::linePath($points) }}" fill="none" stroke="{{ $item['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
                            @endforeach
                        </svg>
                        @foreach (array_reverse($series, true) as $seriesIndex => $item)
                            @php
                                $lastIndex = count($item['data']) - 1;
                                $lastValue = end($item['data']) ?: 0;
                            @endphp
                            @if ($lastIndex >= 0)
                                <span class="absolute size-2.5 -translate-1/2 rounded-full border-2 border-card" style="left: {{ $xFor($lastIndex) }}%; top: {{ $yFor($lastValue) }}%; background: {{ $item['color'] }}"></span>
                                <span @class(['absolute ml-3 -translate-y-1/2 text-[12px] leading-none whitespace-nowrap', 'font-semibold text-ink' => $seriesIndex === 0, 'text-ink-3' => $seriesIndex !== 0]) style="left: {{ $xFor($lastIndex) }}%; top: {{ $endLabelTops[$seriesIndex] ?? 0 }}px">{{ $fmt($lastValue) }}</span>
                            @endif
                            @foreach ($item['data'] as $index => $value)
                                <span class="absolute size-2.5 -translate-1/2 rounded-full border-2 border-card" style="left: {{ $xFor($index) }}%; top: {{ $yFor($value) }}%; background: {{ $item['color'] }}" x-show="hover === {{ $index }}" x-cloak></span>
                            @endforeach
                        @endforeach
                    @else
                        <div role="img" aria-label="{{ $ariaLabel }}" class="absolute inset-0">
                            @foreach ($first['data'] as $index => $value)
                                @php
                                    $isLast = $index === count($first['data']) - 1;
                                @endphp
                                <div class="absolute bottom-0 -translate-x-1/2" style="left: {{ $xFor($index) }}%; width: min(24px, {{ round($slot * 0.5, 3) }}%); height: {{ round(100 - $yFor($value), 3) }}%">
                                    <span class="absolute inset-0 rounded-t-[4px] transition-opacity" style="background: {{ $first['color'] }}" x-bind:opacity="hover === null || hover === {{ $index }} ? 1 : 0.35" x-bind:class="hover !== null && hover !== {{ $index }} && 'opacity-35'"></span>
                                    <span class="absolute bottom-full left-1/2 mb-1.5 -translate-x-1/2 text-[12px] leading-none font-semibold whitespace-nowrap text-ink" @if ($isLast) x-show="hover === null || hover === {{ $index }}" @else x-show="hover === {{ $index }}" x-cloak @endif>{{ $fmt($value) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @foreach (range(0, $count - 1) as $index)
                        <div class="absolute inset-y-0" style="left: {{ round($xFor($index) - $slot / 2, 3) }}%; width: {{ round($slot, 3) }}%" x-on:mouseenter="hover = {{ $index }}"></div>
                    @endforeach

                    @if ($type === 'line')
                        <div
                            role="status"
                            x-show="hover !== null"
                            x-cloak
                            class="pointer-events-none absolute top-0 z-10 flex min-w-[170px] flex-col gap-1.5 rounded-[10px] border border-line bg-card px-3 py-2.5 text-[13px] shadow-[0_10px_30px_rgb(24_24_27/0.12)]"
                            x-data="{ rows: @js($tooltipRows) }"
                            x-bind:style="hover !== null && { left: rows[hover].left + '%', transform: rows[hover].left > 60 ? 'translateX(calc(-100% - 12px))' : 'translateX(12px)' }"
                        >
                            <template x-if="hover !== null">
                                <div class="flex flex-col gap-1.5">
                                    <strong class="font-semibold text-ink" x-text="rows[hover].title"></strong>
                                    <template x-for="row in rows[hover].values" x-bind:key="row.name">
                                        <span class="flex items-center gap-2 text-ink-2">
                                            <span class="size-2 rounded-full" x-bind:style="{ background: row.color }"></span>
                                            <span x-text="row.name"></span>
                                            <span class="ml-auto font-mono text-ink" x-text="row.value"></span>
                                        </span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    @endif
                </div>
            </div>
        @elseif ($type === 'bar')
            @php
                $barMax = max($first['data'] ?: [1]) ?: 1;
            @endphp
            <div role="img" aria-label="{{ $ariaLabel }}" class="flex flex-col gap-3">
                @foreach ($first['data'] as $index => $value)
                    <div class="grid min-h-7 grid-cols-[92px_minmax(0,1fr)] items-center gap-2.5" x-on:mouseenter="hover = {{ $index }}" x-on:mouseleave="hover = null">
                        <span class="truncate text-[13px] text-ink-2">{{ $labels[$index] ?? '' }}</span>
                        <span class="flex items-center gap-2">
                            <span class="h-5 rounded-r-[4px] transition-opacity" style="width: {{ round($value / $barMax * 85, 2) }}%; background: {{ $colors ? $palette[$index % count($palette)] : $first['color'] }}" x-bind:class="hover !== null && hover !== {{ $index }} && 'opacity-35'"></span>
                            <span class="font-mono text-xs text-ink">{{ $fmt($value) }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
        @elseif ($type === 'donut')
            @php
                $arcs = \DevAction\Ui\Support\ChartGeometry::donutArcs($first['data'], 110, 100, 70);
            @endphp
            <div class="flex flex-col items-center gap-3.5">
                <div class="relative size-[220px]">
                    <svg viewBox="0 0 220 220" role="img" aria-label="{{ $ariaLabel }}" class="size-full">
                        @foreach ($arcs as $index => $arc)
                            <path d="{{ $arc['path'] }}" fill="{{ $palette[$index % count($palette)] }}" class="stroke-card" stroke-width="2" stroke-linejoin="round" x-bind:opacity="hover === null || hover === {{ $index }} ? 1 : 0.35" x-on:mouseenter="hover = {{ $index }}" x-on:mouseleave="hover = null" />
                        @endforeach
                    </svg>
                    @if ($center !== null)
                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-[30px] font-semibold tracking-[-0.02em] text-ink">{{ $center }}</span>
                            @if ($centerLabel)
                                <span class="text-xs text-muted">{{ $centerLabel }}</span>
                            @endif
                        </div>
                    @endif
                </div>
                @if ($legend)
                    <div class="flex w-full flex-col gap-0.5">
                        @foreach ($first['data'] as $index => $value)
                            <span class="flex min-h-8 items-center gap-2.5 text-sm text-ink-2" x-on:mouseenter="hover = {{ $index }}" x-on:mouseleave="hover = null">
                                <span class="size-2.5 rounded-[3px]" style="background: {{ $palette[$index % count($palette)] }}"></span>
                                <span class="flex-1">{{ $labels[$index] ?? '' }}</span>
                                <span class="font-mono text-ink">{{ $fmt($value) }}</span>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif ($type === 'sparkline')
            @php
                $sparkMax = max($first['data'] ?: [1]) ?: 1;
                $sparkMin = min($first['data'] ?: [0]);
                $range = ($sparkMax - $sparkMin) ?: 1;
                $sparkCount = max(count($first['data']) - 1, 1);
                $sparkPoints = collect($first['data'])->map(fn (float $value, int $index): array => [4 + 132 * $index / $sparkCount, 42 - ($value - $sparkMin) / $range * 36])->all();
                $sparkLast = end($sparkPoints) ?: [0, 0];
            @endphp
            <svg viewBox="0 0 140 48" role="img" aria-label="{{ $ariaLabel }}" class="h-12 w-[140px]">
                <path d="{{ \DevAction\Ui\Support\ChartGeometry::areaPath($sparkPoints, 48) }}" fill="{{ $first['color'] }}" fill-opacity="0.1" />
                <path d="{{ \DevAction\Ui\Support\ChartGeometry::linePath($sparkPoints) }}" fill="none" stroke="{{ $first['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="{{ $sparkLast[0] }}" cy="{{ $sparkLast[1] }}" r="4" fill="{{ $first['color'] }}" class="stroke-card" stroke-width="2" />
            </svg>
        @endif
    </div>

    @if ($table)
        <div x-show="showTable" x-cloak class="overflow-x-auto rounded-xl border border-line">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="text-left font-mono text-[11px] tracking-[0.08em] text-muted uppercase">
                        <th scope="col" class="border-b border-line px-3.5 py-2.5 font-medium">{{ $type === 'line' || $type === 'column' ? __('Period') : __('Item') }}</th>
                        @foreach ($series as $item)
                            <th scope="col" class="border-b border-line px-3.5 py-2.5 text-right font-medium">{{ $item['name'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (range(0, $count - 1) as $index)
                        <tr>
                            <td class="border-b border-line-soft px-3.5 py-2 text-ink">{{ $labels[$index] ?? $index + 1 }}</td>
                            @foreach ($series as $item)
                                <td class="border-b border-line-soft px-3.5 py-2 text-right font-mono text-ink">{{ isset($item['data'][$index]) ? $fmt($item['data'][$index]) : '—' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
