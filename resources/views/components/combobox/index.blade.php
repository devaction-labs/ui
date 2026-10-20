{{--
    Combobox (select com busca): escolha única numa lista longa, filtrando pelo texto.

    <x-ui::combobox wire:model.live="project" label="Projeto" placeholder="Escolha um projeto" search-placeholder="Filtrar projetos…">
        <x-ui::combobox.option value="igreja" color="#2563EB" meta="12 PRs">Igreja Viva</x-ui::combobox.option>
        <x-ui::combobox.option value="loja" color="#16A34A">Loja Ponto</x-ui::combobox.option>
    </x-ui::combobox>

    wire:model / x-model: no grupo (x-modelable); name: vai para um input escondido
    empty: texto quando o filtro não encontra nada
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => 'Selecione…',
    'searchPlaceholder' => 'Filtrar…',
    'empty' => 'Nada encontrado.',
    'name' => null,
    'value' => null,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $comboboxId = $attributes->get('id') ?? 'ui-combobox-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$placeholder), 0, 8));
@endphp

<div data-field class="flex min-w-0 flex-col gap-1.5">
    @if ($label)
        <x-ui::label :for="$comboboxId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-combobox
        x-data="uiCombobox({ value: @js($value) })"
        x-modelable="value"
        x-on:click.outside="close()"
        x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); close(true) }"
        {{ $attributes->except('id')->class(['relative w-full']) }}
    >
        <button
            type="button"
            id="{{ $comboboxId }}"
            x-ref="trigger"
            x-on:click="toggle()"
            x-on:keydown.arrow-down.prevent="show()"
            aria-haspopup="listbox"
            x-bind:aria-expanded="open"
            aria-controls="{{ $comboboxId }}-listbox"
            @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $comboboxId }}-hint" @endif
            @class([
                'flex h-11 w-full cursor-pointer items-center justify-between gap-2 rounded-xl border bg-card px-3.5 text-left text-[15px] text-ink transition outline-none',
                'border-missing ring-4 ring-missing/10' => $errorMessage,
                'border-line-strong hover:border-line-hover focus-visible:border-ink focus-visible:ring-4 focus-visible:ring-ink/10' => ! $errorMessage,
            ])
            x-bind:class="open && 'border-ink ring-4 ring-ink/10'"
        >
            <span class="flex min-w-0 items-center gap-2 truncate" x-show="selectedLabel" x-html="selectedHtml"></span>
            <span class="truncate text-muted" x-show="! selectedLabel">{{ $placeholder }}</span>
            <x-ui::icon name="arrow-down-01" class="size-4 shrink-0 text-muted transition" x-bind:class="open && 'rotate-180'" />
        </button>

        @if ($name)
            <input type="hidden" x-ref="raw" name="{{ $name }}" value="{{ $value }}" />
        @else
            <input type="hidden" x-ref="raw" value="{{ $value }}" />
        @endif

        <div
            x-show="open"
            x-cloak
            x-transition.opacity.duration.100ms
            class="absolute inset-x-0 top-full z-50 mt-1.5 overflow-hidden rounded-[14px] border border-line bg-card shadow-[0_16px_40px_rgb(24_24_27/0.12)]"
        >
            <label class="flex h-11 items-center gap-2 border-b border-line-soft px-3">
                <x-ui::icon name="search-01" class="size-3.5 text-muted" />
                <span class="sr-only">{{ $searchPlaceholder }}</span>
                <input
                    type="search"
                    x-ref="search"
                    x-model="query"
                    x-on:keydown.arrow-down.prevent="move(1)"
                    x-on:keydown.arrow-up.prevent="move(-1)"
                    x-on:keydown.enter.prevent="chooseActive()"
                    x-on:keydown.tab="close()"
                    placeholder="{{ $searchPlaceholder }}"
                    role="combobox"
                    aria-autocomplete="list"
                    aria-controls="{{ $comboboxId }}-listbox"
                    x-bind:aria-activedescendant="activeValue !== null ? '{{ $comboboxId }}-option-' + activeValue : null"
                    autocomplete="off"
                    class="min-w-0 flex-1 bg-transparent text-sm text-ink outline-none placeholder:text-muted [&::-webkit-search-cancel-button]:hidden"
                />
            </label>
            <div id="{{ $comboboxId }}-listbox" role="listbox" @if ($label) aria-label="{{ $label }}" @endif class="flex max-h-60 flex-col overflow-y-auto p-1.5" data-combobox-id="{{ $comboboxId }}">
                {{ $slot }}
                <span x-show="! hasResults()" class="px-2.5 py-3 text-[13px] text-muted">{{ $empty }}</span>
            </div>
        </div>
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$comboboxId.'-hint'" :message="$errorMessage" />
    @endif
</div>
