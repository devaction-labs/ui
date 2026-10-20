{{--
    Opção de um grupo do menu de filtros. Marcar não fecha o menu.

    <x-ui::filter-menu.option value="backend" color="#2563EB" count="12">Backend</x-ui::filter-menu.option>
--}}
@aware([
    'name' => null,
    'single' => false,
])

@props([
    'value' => null,
    'icon' => null,
    'color' => null,
    'count' => null,
])

@php
    $value ??= trim(strip_tags((string) $slot));
@endphp

<button
    type="button"
    role="{{ $single ? 'menuitemradio' : 'menuitemcheckbox' }}"
    data-filter-option
    x-bind:aria-checked="isChecked(@js($name), @js($value))"
    x-on:click="toggleOption(@js($name), @js($value), @js((bool) $single))"
    x-on:keydown.arrow-down.prevent="focusSibling(1)"
    x-on:keydown.arrow-up.prevent="focusSibling(-1)"
    {{ $attributes->class(['flex min-h-[38px] w-full shrink-0 cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm text-ink outline-none hover:bg-wrap focus-visible:bg-wrap']) }}
>
    <span
        @class([
            'flex size-4 shrink-0 items-center justify-center border transition',
            'rounded-full' => $single,
            'rounded-[5px]' => ! $single,
        ])
        x-bind:class="isChecked(@js($name), @js($value)) ? 'border-primary bg-primary text-on-primary' : 'border-line-hover bg-card text-transparent'"
        aria-hidden="true"
    >
        <x-ui::icon name="tick-02" class="size-3" />
    </span>
    @if ($color)
        <span class="size-2.5 shrink-0 rounded-[3px]" style="background: {{ $color }}"></span>
    @endif
    @if ($icon)
        <x-ui::icon :name="$icon" class="size-4 shrink-0 text-ink-3" />
    @endif
    <span class="flex-1 truncate">{{ $slot }}</span>
    @if ($count !== null)
        <span class="font-mono text-[11px] text-muted">{{ $count }}</span>
    @endif
</button>
