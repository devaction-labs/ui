{{--
    Radio nativo com o visual do kit. Use dentro de x-ui::radio.group.

    <x-ui::radio value="fixo" label="Escopo fechado" description="Valor fixo por marco." />
--}}
@aware([
    'variant' => 'default',
    'name' => null,
])

@props([
    'label' => null,
    'description' => null,
    'icon' => null,
])

@php
    $isDisabled = (bool) $attributes->get('disabled');
    $inputAttributes = $attributes->merge(array_filter(['type' => 'radio', 'name' => $name]));
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
            <input {{ $inputAttributes->class(['size-[18px] cursor-pointer appearance-none rounded-full border-[1.5px] border-muted bg-card transition checked:border-[6px] checked:border-ink focus-visible:outline-none disabled:cursor-not-allowed']) }} />
        </span>
        @if ($label)
            <span class="text-sm font-semibold text-ink">{{ $label }}</span>
        @endif
        @if ($description)
            <span class="text-[13px] leading-[1.45] text-muted">{{ $description }}</span>
        @endif
        {{ $slot }}
    </label>
@else
    <label @class([
        'flex min-h-11 cursor-pointer items-start gap-3 px-1 py-2 text-left',
        'cursor-not-allowed text-muted' => $isDisabled,
    ])>
        <input {{ $inputAttributes->class([
            'mt-px size-5 shrink-0 cursor-pointer appearance-none rounded-full border-[1.5px] border-muted bg-card transition checked:border-[6px] checked:border-ink',
            'focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-card focus-visible:outline-none',
            'disabled:cursor-not-allowed disabled:border-line disabled:bg-wrap',
        ]) }} />
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
