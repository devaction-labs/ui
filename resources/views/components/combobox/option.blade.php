{{--
    Opção do combobox.

    <x-ui::combobox.option value="igreja" color="#2563EB" meta="12 PRs">Igreja Viva</x-ui::combobox.option>
    <x-ui::combobox.option value="api" icon="code" search="backend laravel">API</x-ui::combobox.option>

    search: termos extras para o filtro, além do texto
--}}
@props([
    'value' => null,
    'icon' => null,
    'color' => null,
    'meta' => null,
    'search' => null,
])

@php
    $label = trim(strip_tags((string) $slot));
    $value ??= $label;
    $isDisabled = (bool) $attributes->get('disabled');
@endphp

<button
    type="button"
    role="option"
    data-combobox-option
    data-value="{{ $value }}"
    data-label="{{ $label }}"
    data-search="{{ trim($label.' '.$meta.' '.$search) }}"
    x-bind:id="$el.closest('[data-combobox-id]').dataset.comboboxId + '-option-' + $el.dataset.value"
    x-show="isVisible($el)"
    x-on:click="choose($el)"
    x-on:mouseenter="activeValue = $el.dataset.value"
    x-bind:aria-selected="String(value ?? '') === $el.dataset.value"
    x-bind:class="activeValue === $el.dataset.value && 'bg-wrap'"
    tabindex="-1"
    @disabled($isDisabled)
    {{ $attributes->except('disabled')->class(['flex min-h-[38px] w-full shrink-0 cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm text-ink disabled:cursor-not-allowed disabled:opacity-50']) }}
>
    <span data-combobox-label class="flex min-w-0 flex-1 items-center gap-2.5">
        @if ($color)
            <span class="size-2.5 shrink-0 rounded-[3px]" style="background: {{ $color }}"></span>
        @endif
        @if ($icon)
            <x-ui::icon :name="$icon" class="size-4 shrink-0 text-ink-3" />
        @endif
        <span class="truncate">{{ $slot }}</span>
    </span>
    @if ($meta)
        <span class="shrink-0 font-mono text-[11px] text-muted">{{ $meta }}</span>
    @endif
    <x-ui::icon name="tick-02" class="size-4 shrink-0 text-ink" x-show="String(value ?? '') === $el.closest('[data-combobox-option]').dataset.value" />
</button>
