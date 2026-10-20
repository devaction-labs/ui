{{--
    Pillbox: vários valores em pílulas dentro de um campo (API no estilo Flux).

    Com opções: escolha várias numa lista.
    <x-ui::pillbox wire:model="selectedTags" placeholder="Escolha as tags" searchable>
        <x-ui::pillbox.option value="vip">VIP</x-ui::pillbox.option>
        <x-ui::pillbox.option value="indicacao">Indicação</x-ui::pillbox.option>
    </x-ui::pillbox>

    variant="combobox": digita direto no campo para filtrar. Busca no servidor e criar opção:
    <x-ui::pillbox wire:model.live="selectedTags" variant="combobox" :filter="false">
        <x-slot:input><x-ui::pillbox.input wire:model.live="search" placeholder="Escolha as tags…" /></x-slot:input>
        @foreach ($this->tags as $tag) <x-ui::pillbox.option :value="$tag->id">{{ $tag->name }}</x-ui::pillbox.option> @endforeach
        <x-ui::pillbox.option.create wire:click="createTag" min-length="2">Criar "<span wire:text="search"></span>"</x-ui::pillbox.option.create>
    </x-ui::pillbox>

    Sem opções: texto livre (tags, e-mails), com sugestões opcionais.
    <x-ui::pillbox wire:model="stack" label="Stack do projeto" :suggestions="['Elixir', 'Phoenix', 'Laravel']" />
    ↵ ou vírgula adiciona · ⌫ no campo vazio remove a última · × remove uma pílula

    searchable: busca dentro da lista · search-placeholder: texto dessa busca
    filter: false desliga o filtro do navegador (o servidor filtra)
    slots: input (campo do combobox) · search (busca da lista) · empty (lista vazia)
    wire:model / x-model recebem o array; name envia name[] em formulários comuns
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => null,
    'name' => null,
    'value' => [],
    'suggestions' => [],
    'max' => null,
    'unique' => true,
    'variant' => 'default',
    'searchable' => false,
    'searchPlaceholder' => null,
    'filter' => true,
    'size' => 'base',
    'empty' => null,
])

@php
    $placeholder ??= __('Add…');
    $searchPlaceholder ??= __('Search…');
    $empty ??= __('No results found.');
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? ($errorBag->first($fieldName) ?: $errorBag->first($fieldName.'.*')) : null);
    $inputId = $attributes->get('id') ?? 'ui-pillbox-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$placeholder), 0, 8));
    $jsMax = $max === null ? 'null' : (int) $max;
    $hasOptions = str_contains((string) $slot, 'data-combobox-option');
    $isCombobox = $variant === 'combobox';
@endphp

@if ($hasOptions)
<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label)
        <x-ui::label :for="$inputId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-pillbox
        data-pillbox-options
        x-data="uiCombobox({ value: @js(array_values(array_map('strval', (array) $value))), multiple: true, filter: @js((bool) $filter), clearQuery: @js($isCombobox) })"
        x-modelable="value"
        x-on:click.outside="close()"
        x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); close(true) }"
        {{ $attributes->except(['id', 'class'])->class(['group/combobox relative w-full']) }}
    >
        <div
            x-ref="trigger"
            id="{{ $isCombobox ? $inputId.'-trigger' : $inputId }}"
            @unless ($isCombobox)
                tabindex="0"
                role="combobox"
                aria-haspopup="listbox"
                x-bind:aria-expanded="open"
                aria-controls="{{ $inputId }}-listbox"
                x-on:keydown.enter.prevent="toggle()"
                x-on:keydown.space.prevent="toggle()"
                x-on:keydown.arrow-down.prevent="show()"
            @endunless
            x-on:click="{{ $isCombobox ? "\$root.querySelector('[data-combobox-search]')?.focus(); show()" : 'toggle()' }}"
            @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $inputId }}-hint" @endif
            @class([
                'flex w-full cursor-pointer flex-wrap items-center gap-1.5 rounded-xl border bg-card px-2 transition outline-none',
                'min-h-[46px] py-1.5' => $size !== 'sm',
                'min-h-9 py-1 text-[13px]' => $size === 'sm',
                'border-missing ring-4 ring-missing/10' => $errorMessage,
                'border-line-strong focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10 focus-visible:border-ink focus-visible:ring-4 focus-visible:ring-ink/10' => ! $errorMessage,
            ])
            x-bind:class="open && 'border-ink ring-4 ring-ink/10'"
        >
            <template x-for="item in selectedValues()" x-bind:key="item">
                <span data-pill @class(['inline-flex max-w-full items-center gap-1 rounded-lg border border-line bg-wrap pr-1 pl-2.5 text-[13px] text-ink', 'h-[30px]' => $size !== 'sm', 'h-6' => $size === 'sm'])>
                    <span class="truncate" x-text="labels[item]?.text ?? item"></span>
                    <button
                        type="button"
                        x-on:click.stop="unselect(item)"
                        x-bind:aria-label="@js(__('Remove :item')).replace(':item', labels[item]?.text ?? item)"
                        class="flex size-[22px] shrink-0 cursor-pointer items-center justify-center rounded-md text-ink-3 transition hover:bg-line hover:text-ink"
                    >
                        <x-ui::icon name="cancel-01" class="size-3" />
                    </button>
                </span>
            </template>

            @if ($isCombobox)
                @isset($input)
                    {{ $input }}
                @else
                    <x-ui::pillbox.input :id="$inputId" :placeholder="$placeholder" :listbox="$inputId.'-listbox'" />
                @endisset
            @else
                <span class="min-w-0 flex-1 truncate px-1.5 text-muted" x-show="! selectedValues().length">{{ $placeholder }}</span>
                <x-ui::icon name="arrow-down-01" class="mr-1.5 ml-auto size-4 shrink-0 text-muted transition" x-bind:class="open && 'rotate-180'" />
            @endif
        </div>

        @if ($name)
            <template x-for="item in value" x-bind:key="item">
                <input type="hidden" name="{{ $name }}[]" x-bind:value="item" />
            </template>
        @endif

        <div
            x-show="open"
            x-ui-float="{ match: true }"
            x-cloak
            x-transition.opacity.duration.100ms
            class="absolute inset-x-0 top-full z-50 mt-1.5 overflow-hidden rounded-[14px] border border-line bg-card shadow-[0_16px_40px_rgb(24_24_27/0.12)]"
        >
            @unless ($isCombobox)
                @isset($search)
                    {{ $search }}
                @elseif ($searchable)
                    <x-ui::pillbox.search :placeholder="$searchPlaceholder" :listbox="$inputId.'-listbox'" />
                @endisset
            @endunless
            <div id="{{ $inputId }}-listbox" role="listbox" aria-multiselectable="true" @if ($label) aria-label="{{ $label }}" @endif class="flex max-h-60 flex-col overflow-y-auto p-1.5" data-combobox-id="{{ $inputId }}">
                {{ $slot }}
                @if ($empty instanceof \Illuminate\View\ComponentSlot)
                    {{ $empty }}
                @elseif (! str_contains((string) $slot, 'data-combobox-empty'))
                    <x-ui::combobox.option.empty>{{ $empty }}</x-ui::combobox.option.empty>
                @endif
            </div>
        </div>
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$inputId.'-hint'" :message="$errorMessage" />
    @endif
</div>
@else

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label)
        <x-ui::label :for="$inputId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-pillbox
        x-data="uiPillbox({ value: @js(array_values($value)), suggestions: @js(array_values($suggestions)), max: {{ $jsMax }}, unique: @js((bool) $unique) })"
        x-modelable="value"
        {{ $attributes->except(['id', 'class'])->class(['relative w-full']) }}
    >
        <div
            x-on:click="$refs.draft.focus()"
            @class([
                'flex min-h-[46px] w-full cursor-text flex-wrap items-center gap-1.5 rounded-xl border bg-card px-2 py-1.5 transition',
                'border-missing ring-4 ring-missing/10' => $errorMessage,
                'border-line-strong focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $errorMessage,
            ])
        >
            <template x-for="(pill, index) in value" x-bind:key="pill + index">
                <span data-pill class="inline-flex h-[30px] max-w-full items-center gap-1 rounded-lg border border-line bg-wrap pr-1 pl-2.5 text-[13px] text-ink">
                    <span class="truncate" x-text="pill"></span>
                    <button
                        type="button"
                        x-on:click.stop="remove(index)"
                        x-bind:aria-label="@js(__('Remove :item')).replace(':item', pill)"
                        class="flex size-[22px] shrink-0 cursor-pointer items-center justify-center rounded-md text-ink-3 transition hover:bg-line hover:text-ink"
                    >
                        <x-ui::icon name="cancel-01" class="size-3" />
                    </button>
                    @if ($name)
                        <input type="hidden" name="{{ $name }}[]" x-bind:value="pill" />
                    @endif
                </span>
            </template>
            <input
                id="{{ $inputId }}"
                x-ref="draft"
                x-model="draft"
                x-on:input="typed()"
                x-on:keydown.enter.prevent="add()"
                x-on:keydown.backspace="backspace()"
                x-on:keydown.arrow-down.prevent="move(1)"
                x-on:keydown.arrow-up.prevent="move(-1)"
                x-on:keydown.escape="if (draft) { $event.stopPropagation(); draft = '' }"
                x-on:blur="add()"
                x-bind:disabled="isFull()"
                x-bind:placeholder="isFull() ? '' : @js($placeholder)"
                type="text"
                autocomplete="off"
                @if ($suggestions !== []) role="combobox" aria-autocomplete="list" aria-controls="{{ $inputId }}-listbox" x-bind:aria-expanded="filtered().length > 0" @endif
                @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $inputId }}-hint" @endif
                class="h-[30px] min-w-[90px] flex-1 bg-transparent px-1 text-sm text-ink outline-none placeholder:text-muted disabled:hidden"
            />
        </div>

        @if ($suggestions !== [])
            <div
                id="{{ $inputId }}-listbox"
                role="listbox"
                x-show="filtered().length"
                x-ui-float="{ match: true }"
                x-cloak
                class="absolute inset-x-0 top-full z-50 mt-1.5 flex flex-col rounded-[14px] border border-line bg-card p-1.5 shadow-[0_16px_40px_rgb(24_24_27/0.12)]"
            >
                <template x-for="(item, index) in filtered()" x-bind:key="item">
                    <div
                        role="option"
                        x-on:mousedown.prevent="add(item)"
                        x-bind:aria-selected="activeIndex === index"
                        x-bind:class="activeIndex === index && 'bg-wrap'"
                        class="flex min-h-9 cursor-pointer items-center rounded-[9px] px-2.5 text-sm text-ink hover:bg-wrap"
                        x-text="item"
                    ></div>
                </template>
            </div>
        @endif
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$inputId.'-hint'" :message="$errorMessage" />
    @endif
</div>
@endif
