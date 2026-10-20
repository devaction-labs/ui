{{--
    Separador (API no estilo Flux): linha horizontal, com texto no meio ou vertical entre itens.

    <x-ui::separator />
    <x-ui::separator text="ou continue com" />
    <span class="flex items-center gap-2.5">Hoje <x-ui::separator vertical /> 3 PRs</span>

    variant: default · subtle (linha mais clara)
--}}
@props([
    'text' => null,
    'vertical' => false,
    'variant' => 'default',
])

@php
    $lineColor = $variant === 'subtle' ? 'bg-line-soft' : 'bg-line';
@endphp

@if ($vertical)
    <span data-separator role="separator" aria-orientation="vertical" {{ $attributes->class(['inline-block h-3.5 w-px shrink-0 self-center', $variant === 'subtle' ? 'bg-line' : 'bg-line-hover']) }}></span>
@elseif ($text)
    <div data-separator role="separator" {{ $attributes->class(['flex w-full items-center gap-2.5 text-[13px] text-muted']) }}>
        <span class="h-px flex-1 {{ $lineColor }}"></span>
        <span class="shrink-0">{{ $text }}</span>
        <span class="h-px flex-1 {{ $lineColor }}"></span>
    </div>
@else
    <div data-separator role="separator" {{ $attributes->class(['h-px w-full shrink-0', $lineColor]) }}></div>
@endif
