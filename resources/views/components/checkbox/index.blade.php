{{--
    Checkbox nativo com o visual do kit (API no estilo Flux).

    <x-ui::checkbox wire:model="autoReview" label="Review automático" description="A IA revisa todo PR aberto." />
    <x-ui::checkbox label="Selecionar todos" indeterminate />

    Dentro de x-ui::checkbox.group, o variant do grupo muda o visual (cards, pills).
--}}
@aware([
    'variant' => 'default',
])

@props([
    'label' => null,
    'description' => null,
    'icon' => null,
    'indeterminate' => false,
])

@php
    $isDisabled = (bool) $attributes->get('disabled');
    $inputAttributes = $attributes->merge(['type' => 'checkbox']);
@endphp

@if ($variant === 'cards')
    <label @class([
        'relative flex cursor-pointer flex-col gap-2 rounded-[14px] border-[1.5px] border-line-strong bg-card p-3.5 text-left transition',
        'has-checked:border-ink has-checked:bg-wrap has-focus-visible:ring-2 has-focus-visible:ring-ring',
        'cursor-not-allowed opacity-60' => $isDisabled,
    ])>
        <span class="flex items-center justify-between">
            @if ($icon)
                <x-ui::icon :name="$icon" class="text-ink" />
            @else
                <span></span>
            @endif
            <span class="relative flex size-[18px] items-center justify-center">
                <input {{ $inputAttributes->class(['peer size-[18px] cursor-pointer appearance-none rounded-[5px] border-[1.5px] border-muted bg-card transition checked:border-primary checked:bg-primary focus-visible:outline-none disabled:cursor-not-allowed']) }} />
                <x-ui::icon name="tick-02" stroke-width="2.6" class="pointer-events-none absolute size-3 text-on-ink opacity-0 peer-checked:opacity-100" />
            </span>
        </span>
        @if ($label)
            <span class="text-sm font-semibold text-ink">{{ $label }}</span>
        @endif
        @if ($description)
            <span class="text-[13px] leading-[1.45] text-muted">{{ $description }}</span>
        @endif
        {{ $slot }}
    </label>
@elseif ($variant === 'pills')
    <label @class([
        'inline-flex h-8 cursor-pointer items-center gap-1.5 rounded-full border border-line-strong bg-card px-3.5 text-sm text-ink-2 transition hover:border-line-hover',
        'has-checked:border-primary has-checked:bg-primary has-checked:text-on-primary has-focus-visible:ring-2 has-focus-visible:ring-ring',
        'cursor-not-allowed opacity-60' => $isDisabled,
    ])>
        <input {{ $inputAttributes->class(['sr-only']) }} />
        @if ($icon)
            <x-ui::icon :name="$icon" class="size-4" />
        @endif
        {{ $label }}{{ $slot }}
    </label>
@else
    <label @class([
        'flex min-h-11 cursor-pointer items-start gap-3 px-1 py-2.5 text-left',
        'cursor-not-allowed text-muted' => $isDisabled,
    ])>
        <span class="relative mt-px flex size-5 shrink-0 items-center justify-center">
            <input
                @if ($indeterminate) x-data x-init="$el.indeterminate = true" @endif
                {{ $inputAttributes->class([
                    'peer size-5 cursor-pointer appearance-none rounded-md border-[1.5px] border-muted bg-card transition',
                    'checked:border-primary checked:bg-primary indeterminate:border-primary indeterminate:bg-primary',
                    'focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-card focus-visible:outline-none',
                    'disabled:cursor-not-allowed disabled:border-line disabled:bg-wrap',
                ]) }}
            />
            <x-ui::icon name="tick-02" stroke-width="2.6" class="pointer-events-none absolute size-[13px] text-on-ink opacity-0 peer-checked:opacity-100 peer-indeterminate:opacity-0" />
            <x-ui::icon name="minus-sign" stroke-width="2.6" class="pointer-events-none absolute size-[13px] text-on-ink opacity-0 peer-indeterminate:opacity-100" />
        </span>
        @if ($label || $description || $slot->isNotEmpty())
            <span class="flex min-w-0 flex-col gap-0.5">
                @if ($label)
                    <span @class(['text-sm font-medium', 'text-ink' => ! $isDisabled])>{{ $label }}</span>
                @endif
                @if ($description)
                    <span class="text-[13px] text-muted">{{ $description }}</span>
                @endif
                {{ $slot }}
            </span>
        @endif
    </label>
@endif
