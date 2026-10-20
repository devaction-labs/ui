{{--
    Pillbox: vários textos em pílulas dentro de um campo (tags, stack, e-mails).

    <x-ui::pillbox wire:model="stack" label="Stack do projeto" :suggestions="['Elixir', 'Phoenix', 'Laravel']" />
    <x-ui::pillbox name="emails" :value="['ana@acme.com']" placeholder="Adicionar e-mail…" max="5" />

    ↵ ou vírgula adiciona · ⌫ no campo vazio remove a última · × remove uma pílula
    wire:model / x-model recebem o array; name envia name[] em formulários comuns
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => 'Adicionar…',
    'name' => null,
    'value' => [],
    'suggestions' => [],
    'max' => null,
    'unique' => true,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? ($errorBag->first($fieldName) ?: $errorBag->first($fieldName.'.*')) : null);
    $inputId = $attributes->get('id') ?? 'ui-pillbox-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$placeholder), 0, 8));
    $jsMax = $max === null ? 'null' : (int) $max;
@endphp

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
                        x-bind:aria-label="'Remover ' + pill"
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
