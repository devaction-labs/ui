{{-- Chart: line with a data table, column, and a row of bar, donut and sparkline charts — see references/chart.md --}}
@php
    $weeks = ['w34', 'w35', 'w36', 'w37', 'w38', 'w39', 'w40', 'w41'];
    $prs = [12, 18, 15, 22, 19, 26, 24, 31];
@endphp

<x-ui::chart
    type="line"
    label="Actual and forecast revenue from March to October"
    :labels="['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct']"
    format="$:valuek"
    table
    :series="[
        ['name' => 'Actual', 'data' => [38, 42, 47, 45, 52, 58, 61, 66]],
        ['name' => 'Forecast', 'data' => [40, 44, 46, 50, 54, 56, 60, 64], 'color' => '#C2850F'],
    ]"
/>

{{-- Variation: column chart with one series from a @php array --}}
<x-ui::chart
    type="column"
    class="mt-8"
    label="PRs reviewed per week over the last 8 weeks"
    :labels="$weeks"
    :series="[['name' => 'PRs', 'data' => $prs]]"
/>

<div class="mt-8 grid w-full items-start gap-8 md:grid-cols-3">
    <x-ui::chart
        type="bar"
        label="Hours per project in October"
        format=":value h"
        :labels="['SGE', 'Ponto Store', 'Clinic', 'School', 'Internal']"
        :series="[['name' => 'Hours', 'data' => [132, 48, 64, 22, 18]]]"
    />
    <x-ui::chart
        type="donut"
        label="Scope: 18 done, 4 in review, 3 without PR, 1 out of scope"
        center="69%"
        center-label="done"
        :labels="['Done', 'In review', 'No PR', 'Out of scope']"
        :colors="['#16A34A', '#C2850F', '#DC2626', '#A1A1AA']"
        :series="[['name' => 'Items', 'data' => [18, 4, 3, 1]]]"
    />
    <div class="flex items-end gap-4 rounded-2xl border border-line p-4">
        <div class="flex flex-1 flex-col gap-1.5">
            <span class="font-mono text-[11px] tracking-[0.08em] text-muted">WEEKLY REVENUE</span>
            <span class="text-[28px] font-semibold tracking-[-0.03em] tabular-nums">$18.4k</span>
        </div>
        <x-ui::chart type="sparkline" label="Revenue rising over the last 8 weeks" :series="[['data' => [9, 11, 10, 13, 12, 15, 16, 18]]]" class="w-auto" />
    </div>
</div>
