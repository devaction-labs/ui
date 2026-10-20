{{--
    Caixa de busca do combobox. Use no slot input para levar o texto ao servidor:

    <x-slot:input><x-ui::combobox.input wire:model.live.debounce.250ms="companySearch" placeholder="Buscar empresa…" /></x-slot:input>

    Os outros atributos (wire:model, x-model…) vão para o input.
--}}
@props([
    'placeholder' => 'Filtrar…',
    'listbox' => null,
])

<label class="flex h-11 items-center gap-2 border-b border-line-soft px-3">
    <x-ui::icon name="search-01" class="size-3.5 text-muted" />
    <span class="sr-only">{{ $placeholder }}</span>
    <input
        type="search"
        data-combobox-search
        x-model="query"
        x-on:keydown.arrow-down.prevent="move(1)"
        x-on:keydown.arrow-up.prevent="move(-1)"
        x-on:keydown.enter.prevent="chooseActive()"
        x-on:keydown.tab="close()"
        placeholder="{{ $placeholder }}"
        role="combobox"
        aria-autocomplete="list"
        @if ($listbox) aria-controls="{{ $listbox }}" @endif
        x-bind:aria-activedescendant="activeValue !== null ? $root.querySelector('[data-combobox-id]').dataset.comboboxId + '-option-' + activeValue : null"
        autocomplete="off"
        {{ $attributes->class(['min-w-0 flex-1 bg-transparent text-sm text-ink outline-none placeholder:text-muted [&::-webkit-search-cancel-button]:hidden']) }}
    />
</label>
