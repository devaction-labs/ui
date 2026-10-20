<?php

use DevAction\Ui\Support\ChartGeometry;
use Illuminate\Support\Facades\Blade;

test('chart scale rounds the axis to friendly steps', function () {
    expect(ChartGeometry::scale(66))->toMatchArray(['max' => 80.0, 'step' => 20.0, 'ticks' => [0.0, 20.0, 40.0, 60.0, 80.0]]);
    expect(ChartGeometry::scale(31)['max'])->toBe(40.0);
    expect(ChartGeometry::scale(0)['max'])->toBe(1.0);
});

test('chart geometry builds paths and formats values', function () {
    expect(ChartGeometry::linePath([[0, 10], [50, 0]]))->toBe('M0 10 L50 0');
    expect(ChartGeometry::areaPath([[0, 10], [50, 0]], 20))->toBe('M0 10 L50 0 L50 20 L0 20 Z');
    expect(ChartGeometry::donutArcs([3, 1], 110, 100, 70))->toHaveCount(2)
        ->and(ChartGeometry::donutArcs([3, 1], 110, 100, 70)[0]['percent'])->toBe(75.0);
    expect(ChartGeometry::format(18400, 'R$ :value'))->toBe('R$ 18.400');
});

test('line chart renders series, axis, tooltip data and table', function () {
    $html = Blade::render('<x-ui::chart type="line" label="Faturamento" :labels="[\'mar\', \'abr\']" format="R$ :value mil" table :series="[[\'name\' => \'Realizado\', \'data\' => [38, 66]], [\'name\' => \'Previsto\', \'data\' => [40, 64], \'color\' => \'#C2850F\']]" />');

    expect($html)
        ->toContain('data-type="line"')
        ->toContain('role="img"')
        ->toContain('aria-label="Faturamento"')
        ->toContain('stroke="#2563EB"')
        ->toContain('stroke="#C2850F"')
        ->toContain('fill-opacity="0.08"')
        ->toContain('R$ 66 mil')
        ->toContain('>mar</span>')
        ->toContain('preserveAspectRatio="none"')
        ->toContain('vector-effect="non-scaling-stroke"')
        ->not->toContain('viewBox="0 0 820')
        ->toContain('role="status"')
        ->toContain('Ver tabela')
        ->toContain('<table');
});

test('column, bar, donut and sparkline charts render', function (string $type, string $expected) {
    $html = Blade::render("<x-ui::chart type=\"{$type}\" :labels=\"['A', 'B']\" center=\"69%\" :series=\"[['name' => 'X', 'data' => [3, 1]]]\" />");

    expect($html)->toContain('data-type="'.$type.'"')->toContain($expected);
})->with([
    ['column', 'x-bind:opacity'],
    ['bar', 'grid-cols-[92px_minmax(0,1fr)]'],
    ['donut', '69%'],
    ['sparkline', 'viewBox="0 0 140 48"'],
]);
