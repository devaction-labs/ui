{{--
    Opção do controle segmentado.

    <x-ui::segmented.option value="semana" count="12">Semana</x-ui::segmented.option>
    <x-ui::segmented.option value="quadro" icon="kanban" aria-label="Quadro" />
--}}
@aware([
    'name' => null,
    'size' => 'base',
    'full' => false,
])

@props([
    'value' => null,
    'icon' => null,
    'count' => null,
])

@php
    $value ??= trim(strip_tags((string) $slot));
    $ariaLabel = $attributes->get('aria-label');
    $isDisabled = (bool) $attributes->get('disabled');
    $inputAttributes = $attributes->except(['aria-label', 'class'])->merge(array_filter([
        'type' => 'radio',
        'name' => $name,
        'value' => $value,
        'aria-label' => $ariaLabel,
    ]));
@endphp

<label @class([
    'inline-flex cursor-pointer items-center justify-center gap-1.5 font-medium whitespace-nowrap text-ink-3 transition hover:text-ink',
    'has-checked:bg-card has-checked:text-ink has-checked:shadow-[0_1px_2px_rgb(24_24_27/0.12)]',
    'has-focus-visible:ring-2 has-focus-visible:ring-ring',
    'h-7 rounded-md px-2.5 text-[13px]' => $size === 'sm',
    'h-[34px] rounded-lg px-3 text-sm' => $size !== 'sm',
    'flex-1' => $full,
    'cursor-not-allowed opacity-50' => $isDisabled,
    $attributes->get('class'),
])>
    <input {{ $inputAttributes->class(['sr-only']) }} />
    @if ($icon)
        <x-ui::icon :name="$icon" class="size-4" />
    @endif
    {{ $slot }}
    @if ($count !== null)
        <span class="font-mono text-[11px] font-normal text-muted">{{ $count }}</span>
    @endif
</label>
