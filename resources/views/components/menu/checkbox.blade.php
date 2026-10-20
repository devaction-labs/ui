{{--
    Item marcável do menu. Funciona com wire:model.

    <x-ui::menu.checkbox wire:model.live="showDrafts" keep-open>Rascunhos</x-ui::menu.checkbox>
--}}
@props([
    'checked' => false,
    'disabled' => false,
    'keepOpen' => false,
])

<button
    type="button"
    data-menu-item
    role="menuitemcheckbox"
    tabindex="-1"
    x-data="{ checked: @js((bool) $checked) }"
    x-modelable="checked"
    aria-checked="{{ $checked ? 'true' : 'false' }}"
    x-bind:aria-checked="checked.toString()"
    x-on:click="checked = ! checked; if (! @js((bool) $keepOpen) && ! $el.closest('[data-keep-open]')) close()"
    @disabled($disabled)
    {{ $attributes->class([
        'group/check flex h-[38px] w-full shrink-0 cursor-pointer items-center gap-2.5 rounded-[9px] px-2.5 text-left text-sm text-ink outline-none transition-colors hover:bg-wrap focus:bg-wrap',
        'disabled:cursor-not-allowed disabled:opacity-45',
    ]) }}
>
    <span class="flex size-4 items-center justify-center rounded-[5px] border border-line-strong group-aria-checked/check:border-ink group-aria-checked/check:bg-ink group-aria-checked/check:text-on-ink">
        <x-ui::icon name="tick-02" stroke-width="2.2" class="size-3 opacity-0 group-aria-checked/check:opacity-100" />
    </span>
    <span class="min-w-0 flex-1 truncate">{{ $slot }}</span>
</button>
