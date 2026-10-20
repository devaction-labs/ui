{{--
    Item da lista de dados.

    <x-ui::description-list.item label="Marco atual">M3 · Relatórios financeiros</x-ui::description-list.item>
    <x-ui::description-list.item label="Repositório" mono copyable>devaction-labs/sge-api</x-ui::description-list.item>

    mono: valor em Geist Mono · copyable: botão de copiar o valor
--}}
@aware([
    'columns' => 1,
])

@props([
    'label',
    'mono' => false,
    'copyable' => false,
])

<div
    data-description-item
    @class([
        'grid gap-1 border-b border-line-soft py-2.5',
        'sm:grid-cols-[var(--description-label)_minmax(0,1fr)] sm:gap-3' => (int) $columns !== 2,
    ])
>
    <dt class="text-muted">{{ $label }}</dt>
    <dd {{ $attributes->class(['flex min-w-0 items-center gap-2 font-medium text-ink', 'font-mono text-[13px]' => $mono]) }}>
        <span class="min-w-0 break-words">{{ $slot }}</span>
        @if ($copyable)
            <button
                type="button"
                x-data="{ copied: false }"
                x-on:click="navigator.clipboard?.writeText($el.previousElementSibling.textContent.trim()); copied = true; setTimeout(() => copied = false, 1500)"
                x-bind:aria-label="copied ? @js(__('Copied')) : @js(__('Copy :label', ['label' => $label]))"
                aria-label="{{ __('Copy :label', ['label' => $label]) }}"
                class="inline-flex size-6 shrink-0 cursor-pointer items-center justify-center rounded-md text-muted transition hover:bg-wrap hover:text-ink"
            >
                <x-ui::icon name="copy-01" class="size-3.5" x-show="! copied" />
                <x-ui::icon name="tick-02" class="size-3.5 text-ok-fg" x-show="copied" x-cloak />
            </button>
        @endif
    </dd>
</div>
