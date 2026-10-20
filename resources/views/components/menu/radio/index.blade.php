{{--
    Opção de um x-ui::menu.radio.group. O check aparece à direita da escolhida, como no design.

    <x-ui::menu.radio value="prazo" checked>Por prazo</x-ui::menu.radio>
--}}
@props([
    'value' => null,
    'checked' => false,
    'disabled' => false,
    'keepOpen' => false,
])

@php
    $value ??= trim(strip_tags((string) $slot));
@endphp

<button
    type="button"
    data-menu-item
    role="menuitemradio"
    tabindex="-1"
    @if ($checked) x-init="value = @js($value)" @endif
    aria-checked="{{ $checked ? 'true' : 'false' }}"
    x-bind:aria-checked="(value === @js($value)).toString()"
    x-on:click="value = @js($value); if (! @js((bool) $keepOpen) && ! $el.closest('[data-keep-open]')) close()"
    @disabled($disabled)
    {{ $attributes->class([
        'group/radio flex h-[38px] w-full shrink-0 cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm text-ink outline-none transition-colors hover:bg-wrap focus:bg-wrap aria-checked:bg-wrap',
        'disabled:cursor-not-allowed disabled:opacity-45',
    ]) }}
>
    <span class="min-w-0 flex-1 truncate">{{ $slot }}</span>
    <x-ui::icon name="tick-02" stroke-width="2.2" class="size-3.5 opacity-0 group-aria-checked/radio:opacity-100" />
</button>
