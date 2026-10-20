{{--
    Segmento de uma barra empilhada. A largura é value em relação ao max do progress pai.

    <x-ui::progress.segment value="1" color="green" />
--}}
@aware([
    'max' => 100,
])

@props([
    'value' => 0,
    'color' => null,
])

@php
    $width = round(min(max((float) $value, 0), max((float) $max, 1)) / max((float) $max, 1) * 100, 2);

    $fillClasses = match ($color) {
        'amber', 'orange', 'yellow', 'ai' => 'bg-ai',
        'green', 'emerald', 'lime', 'teal' => 'bg-ok-fg',
        'red', 'rose', 'pink' => 'bg-missing',
        'blue', 'sky', 'cyan' => 'bg-learning',
        'indigo', 'violet', 'purple', 'fuchsia' => 'bg-violet',
        default => 'bg-primary',
    };
@endphp

<span data-progress-segment style="width: {{ $width }}%" {{ $attributes->class(['h-full rounded-full', $fillClasses]) }}></span>
