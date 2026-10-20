{{--
    Item da lista ordenável.

    <x-ui::sortable-list.item key="m3" label="Relatórios financeiros">
        <span class="font-mono text-xs text-muted">M3</span> Relatórios financeiros
        <x-slot:meta>16 out</x-slot:meta>
    </x-ui::sortable-list.item>

    label: nome usado nos avisos ("Relatórios financeiros movido para a posição 2 de 5"); padrão: o texto do item
--}}
@aware([
    'buttons' => true,
])

@props([
    'key',
    'label' => null,
])

<li
    data-sortable-item
    data-key="{{ $key }}"
    data-label="{{ $label ?? trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot))) }}"
    x-bind:class="grabbed === $el.dataset.key ? 'border-ink shadow-[0_10px_24px_-10px_rgb(24_24_27/0.35)] scale-[1.01]' : 'border-line'"
    {{ $attributes->class(['flex min-h-[50px] items-center gap-2.5 rounded-xl border bg-card px-2.5 py-2 text-sm transition-[transform,box-shadow,border-color] duration-150']) }}
>
    <button
        type="button"
        data-sortable-handle
        x-on:pointerdown="startDrag($event, $el.closest('[data-sortable-item]'))"
        x-on:keydown="onKeydown($event, $el.closest('[data-sortable-item]'))"
        x-on:blur="drop($el.closest('[data-sortable-item]'), true)"
        x-bind:aria-pressed="(grabbed === $el.closest('[data-sortable-item]').dataset.key).toString()"
        aria-label="Mover {{ $label ?? trim(preg_replace('/\s+/', ' ', strip_tags((string) $slot))) }}"
        class="flex size-7 shrink-0 cursor-grab touch-none items-center justify-center rounded-md text-muted transition hover:bg-wrap hover:text-ink focus-visible:ring-2 focus-visible:ring-ring active:cursor-grabbing"
    >
        <x-ui::icon name="drag-drop-vertical" class="size-4" />
    </button>
    <span class="flex min-w-0 flex-1 items-center gap-2.5 font-medium">{{ $slot }}</span>
    @isset($meta)
        <span class="shrink-0 text-xs text-muted">{{ $meta }}</span>
    @endisset
    @if ($buttons)
        <span class="flex shrink-0 gap-1">
            <button type="button" x-on:click="moveBy($el.closest('[data-sortable-item]'), -1)" x-bind:disabled="isFirst($el.closest('[data-sortable-item]'))" aria-label="Mover para cima" class="flex size-[26px] cursor-pointer items-center justify-center rounded-[7px] border border-line bg-card transition hover:border-line-hover disabled:cursor-default disabled:opacity-35"><x-ui::icon name="arrow-up-01" class="size-[13px]" /></button>
            <button type="button" x-on:click="moveBy($el.closest('[data-sortable-item]'), 1)" x-bind:disabled="isLast($el.closest('[data-sortable-item]'))" aria-label="Mover para baixo" class="flex size-[26px] cursor-pointer items-center justify-center rounded-[7px] border border-line bg-card transition hover:border-line-hover disabled:cursor-default disabled:opacity-35"><x-ui::icon name="arrow-down-01" class="size-[13px]" /></button>
        </span>
    @endif
</li>
