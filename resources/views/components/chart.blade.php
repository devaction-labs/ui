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
        'name' => $item['name'] ?? 'Série '.($index + 1),
        'data' => array_map('floatval', array_values($item['data'] ?? [])),
        'color' => $item['color'] ?? $palette[$index % count($palette)],
    ])->all();
    $first = $series[0] ?? ['name' => '', 'data' => [], 'color' => $palette[0]];
    $fmt = fn (float $value): string => \DevAction\Ui\Support\ChartGeometry::format($value, $format, (int) $decimals);
    $legend ??= ($type === 'line' && count($series) > 1) || $type === 'donut';
    $ariaLabel = $label ?? collect($series)->pluck('name')->implode(', ');

    $width = 820;
    $chartHeight = (float) ($height ?? ($type === 'column' ? 220 : 280));
    $plotLeft = 44;
    $plotRight = $type === 'line' ? 744 : 800;
    $plotTop = 16;
    $plotBottom = $chartHeight - 30;
    $count = max(count($labels), count($first['data']), 1);

    $maximum = collect($series)->flatMap(fn (array $item): array => $item['data'])->max() ?? 0;
    $scale = \DevAction\Ui\Support\ChartGeometry::scale((float) $maximum);
    $yFor = fn (float $value): float => $plotBottom - ($value / $scale['max']) * ($plotBottom - $plotTop);

    $slot = ($plotRight - $plotLeft) / $count;
    $xFor = $type === 'line'
        ? fn (int $index): float => $count > 1 ? $plotLeft + ($plotRight - $plotLeft) * $index / ($count - 1) : ($plotLeft + $plotRight) / 2
        : fn (int $index): float => $plotLeft + $slot * ($index + 0.5);

    $tooltipRows = collect(range(0, $count - 1))->map(fn (int $index): array => [
        'title' => $labels[$index] ?? (string) ($index + 1),
        'values' => collect($series)->map(fn (array $item): array => [
            'name' => $item['name'],
            'color' => $item['color'],
            'value' => isset($item['data'][$index]) ? $fmt($item['data'][$index]) : '—',
        ])->all(),
        'left' => round($xFor($index) / $width * 100, 2),
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
                    <span x-text="showTable ? 'Ver gráfico' : 'Ver tabela'">Ver tabela</span>
                </x-ui::button>
            @endif
        </div>
    @endif

    <div x-show="! showTable">
        @if ($type === 'line' || $type === 'column')
            <div class="relative">
                <svg viewBox="0 0 {{ $width }} {{ $chartHeight }}" role="img" aria-label="{{ $ariaLabel }}" class="block w-full overflow-visible" x-on:mouseleave="hover = null">
                    @foreach ($scale['ticks'] as $tick)
                        <line x1="{{ $plotLeft }}" x2="{{ $type === 'line' ? $plotRight + 56 : $plotRight }}" y1="{{ $yFor($tick) }}" y2="{{ $yFor($tick) }}" class="stroke-line-soft" stroke-width="1" />
                        <text x="{{ $plotLeft - 8 }}" y="{{ $yFor($tick) + 4 }}" text-anchor="end" class="fill-muted font-mono text-[12px]">{{ \DevAction\Ui\Support\ChartGeometry::format($tick, ':value', $scale['step'] < 1 ? 1 : 0) }}</text>
                    @endforeach
                    @foreach ($labels as $index => $text)
                        <text x="{{ $xFor($index) }}" y="{{ $chartHeight - 10 }}" text-anchor="middle" class="fill-muted text-[12px]" x-bind:class="hover === {{ $index }} && 'fill-ink!'">{{ $text }}</text>
                    @endforeach

                    @if ($type === 'line')
                        @foreach (array_reverse($series, true) as $seriesIndex => $item)
                            @php
                                $points = collect($item['data'])->map(fn (float $value, int $index): array => [$xFor($index), $yFor($value)])->all();
                                $last = end($points) ?: [0, 0];
                            @endphp
                            @if ($seriesIndex === 0)
                                <path d="{{ \DevAction\Ui\Support\ChartGeometry::areaPath($points, $plotBottom) }}" fill="{{ $item['color'] }}" fill-opacity="0.08" />
                            @endif
                            <path d="{{ \DevAction\Ui\Support\ChartGeometry::linePath($points) }}" fill="none" stroke="{{ $item['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="{{ $last[0] }}" cy="{{ $last[1] }}" r="5" fill="{{ $item['color'] }}" class="stroke-card" stroke-width="2" />
                            <text x="{{ $last[0] + 12 }}" y="{{ $last[1] + 4 }}" @class(['text-[12px]', 'fill-ink font-semibold' => $seriesIndex === 0, 'fill-ink-3' => $seriesIndex !== 0])>{{ $fmt(end($item['data']) ?: 0) }}</text>
                            @foreach ($points as $index => $point)
                                <circle cx="{{ $point[0] }}" cy="{{ $point[1] }}" r="5" fill="{{ $item['color'] }}" class="stroke-card" stroke-width="2" x-show="hover === {{ $index }}" x-cloak />
                            @endforeach
                        @endforeach
                        @foreach (range(0, $count - 1) as $index)
                            <line x1="{{ $xFor($index) }}" x2="{{ $xFor($index) }}" y1="{{ $plotTop }}" y2="{{ $plotBottom }}" class="stroke-line-hover" stroke-width="1" x-show="hover === {{ $index }}" x-cloak />
                        @endforeach
                    @else
                        @php
                            $columnWidth = min(24, $slot * 0.5);
                        @endphp
                        @foreach ($first['data'] as $index => $value)
                            @php
                                $top = $yFor($value);
                                $isLast = $index === count($first['data']) - 1;
                            @endphp
                            <path d="{{ \DevAction\Ui\Support\ChartGeometry::columnPath($xFor($index) - $columnWidth / 2, $top, $columnWidth, max(0.01, $plotBottom - $top)) }}" fill="{{ $first['color'] }}" x-bind:opacity="hover === null || hover === {{ $index }} ? 1 : 0.35" />
                            <text x="{{ $xFor($index) }}" y="{{ $top - 8 }}" text-anchor="middle" class="fill-ink text-[12px] font-semibold" @if ($isLast) x-show="hover === null || hover === {{ $index }}" @else x-show="hover === {{ $index }}" x-cloak @endif>{{ $fmt($value) }}</text>
                        @endforeach
                    @endif

                    @foreach (range(0, $count - 1) as $index)
                        <rect x="{{ $xFor($index) - $slot / 2 }}" y="0" width="{{ $slot }}" height="{{ $plotBottom }}" fill="transparent" x-on:mouseenter="hover = {{ $index }}" />
                    @endforeach
                </svg>

                @if ($type === 'line')
                    <div
                        role="status"
                        x-show="hover !== null"
                        x-cloak
                        class="pointer-events-none absolute top-1 flex min-w-[170px] flex-col gap-1.5 rounded-[10px] border border-line bg-card px-3 py-2.5 text-[13px] shadow-[0_10px_30px_rgb(24_24_27/0.12)]"
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
                        <th scope="col" class="border-b border-line px-3.5 py-2.5 font-medium">{{ $type === 'line' || $type === 'column' ? 'Período' : 'Item' }}</th>
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
