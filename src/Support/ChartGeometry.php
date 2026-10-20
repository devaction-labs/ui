<?php

namespace DevAction\Ui\Support;

/**
 * Geometria dos gráficos do x-ui::chart, calculada no servidor para gerar SVG sem dependências.
 */
class ChartGeometry
{
    /**
     * Cores padrão das séries, na ordem.
     *
     * @var list<string>
     */
    public const PALETTE = ['#2563EB', '#C2850F', '#16A34A', '#7C3AED', '#DC2626', '#0891B2'];

    /**
     * Escala "redonda" para o eixo: máximo e passo múltiplos de 1, 2, 2,5 ou 5.
     *
     * @return array{max: float, step: float, ticks: list<float>}
     */
    public static function scale(float $maximum, int $tickCount = 4): array
    {
        if ($maximum <= 0) {
            return ['max' => 1.0, 'step' => 0.25, 'ticks' => [0.0, 0.25, 0.5, 0.75, 1.0]];
        }

        $rawStep = $maximum / $tickCount;
        $magnitude = 10 ** floor(log10($rawStep));
        $step = $magnitude;

        foreach ([1, 2, 2.5, 5, 10] as $multiplier) {
            if ($rawStep <= $multiplier * $magnitude) {
                $step = $multiplier * $magnitude;

                break;
            }
        }

        $max = ceil($maximum / $step) * $step;
        $ticks = [];

        for ($value = 0.0; $value <= $max + $step / 2; $value += $step) {
            $ticks[] = round($value, 6);
        }

        return ['max' => (float) $max, 'step' => (float) $step, 'ticks' => $ticks];
    }

    /**
     * Caminho SVG de uma linha que passa pelos pontos.
     *
     * @param  list<array{0: float, 1: float}>  $points
     */
    public static function linePath(array $points): string
    {
        return collect($points)
            ->map(fn (array $point, int $index): string => ($index === 0 ? 'M' : 'L').self::number($point[0]).' '.self::number($point[1]))
            ->implode(' ');
    }

    /**
     * Caminho SVG da área entre a linha e a base.
     *
     * @param  list<array{0: float, 1: float}>  $points
     */
    public static function areaPath(array $points, float $baseline): string
    {
        if ($points === []) {
            return '';
        }

        $first = $points[0];
        $last = $points[count($points) - 1];

        return self::linePath($points).' L'.self::number($last[0]).' '.self::number($baseline).' L'.self::number($first[0]).' '.self::number($baseline).' Z';
    }

    /**
     * Coluna com a ponta arredondada (raio de até 4 px) e a base reta.
     */
    public static function columnPath(float $x, float $y, float $width, float $height): string
    {
        $radius = min(4, $width / 2, $height);
        $bottom = $y + $height;

        return 'M'.self::number($x).' '.self::number($bottom)
            .' V'.self::number($y + $radius)
            .' Q'.self::number($x).' '.self::number($y).' '.self::number($x + $radius).' '.self::number($y)
            .' H'.self::number($x + $width - $radius)
            .' Q'.self::number($x + $width).' '.self::number($y).' '.self::number($x + $width).' '.self::number($y + $radius)
            .' V'.self::number($bottom).' Z';
    }

    /**
     * Fatias de um donut, com espaço entre elas (o espaço vem do traço branco no SVG).
     *
     * @param  list<float>  $values
     * @return list<array{path: string, percent: float}>
     */
    public static function donutArcs(array $values, float $center, float $outerRadius, float $innerRadius): array
    {
        $total = array_sum($values);

        if ($total <= 0) {
            return [];
        }

        $angle = -M_PI / 2;
        $arcs = [];

        foreach ($values as $value) {
            $sweep = ($value / $total) * 2 * M_PI;

            if ($sweep >= 2 * M_PI - 0.0001) {
                $sweep = 2 * M_PI - 0.0001;
            }

            $end = $angle + $sweep;
            $largeArc = $sweep > M_PI ? 1 : 0;

            [$x1, $y1] = [$center + $outerRadius * cos($angle), $center + $outerRadius * sin($angle)];
            [$x2, $y2] = [$center + $outerRadius * cos($end), $center + $outerRadius * sin($end)];
            [$x3, $y3] = [$center + $innerRadius * cos($end), $center + $innerRadius * sin($end)];
            [$x4, $y4] = [$center + $innerRadius * cos($angle), $center + $innerRadius * sin($angle)];

            $arcs[] = [
                'path' => 'M'.self::number($x1).' '.self::number($y1)
                    .' A'.self::number($outerRadius).' '.self::number($outerRadius).' 0 '.$largeArc.' 1 '.self::number($x2).' '.self::number($y2)
                    .' L'.self::number($x3).' '.self::number($y3)
                    .' A'.self::number($innerRadius).' '.self::number($innerRadius).' 0 '.$largeArc.' 0 '.self::number($x4).' '.self::number($y4)
                    .' Z',
                'percent' => round($value / $total * 100, 1),
            ];

            $angle = $end;
        }

        return $arcs;
    }

    /**
     * Formata um valor com um molde como "R$ :value mil" e separadores do pt-BR.
     */
    public static function format(float|int $value, string $template = ':value', int $decimals = 0): string
    {
        return str_replace(':value', number_format((float) $value, $decimals, ',', '.'), $template);
    }

    protected static function number(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }
}
