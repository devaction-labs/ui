{{--
    Autocomplete: campo de texto livre com sugestões, agrupadas ou não. O trecho digitado fica em negrito.

    <x-ui::autocomplete wire:model.live="search" placeholder="Buscar lições e PRs…">
        <x-ui::autocomplete.group heading="Lições">
            <x-ui::autocomplete.item value="Ecto.Multi e rollback" badge="30" />
        </x-ui::autocomplete.group>
    </x-ui::autocomplete>

    class vai para o campo; wire:model, name e os outros atributos vão para o input; o valor é o texto digitado ou o item escolhido.
    min-chars: quantos caracteres abrem as sugestões (padrão 1)
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'icon' => 'search-01',
    'minChars' => 1,
    'empty' => null,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $attributes->get('name') ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $inputId = $attributes->get('id') ?? 'ui-autocomplete-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$attributes->get('placeholder')), 0, 8));
@endphp

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label)
        <x-ui::label :for="$inputId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-autocomplete
        x-data="uiAutocomplete({ minChars: {{ (int) $minChars }} })"
        x-on:click.outside="open = false"
        class="relative w-full"
    >
        <div @class([
            'flex h-11 w-full items-center gap-2.5 rounded-xl border bg-card px-3.5 transition',
            'border-missing ring-4 ring-missing/10' => $errorMessage,
            'border-line-strong focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $errorMessage,
        ])>
            @if ($icon)
                <x-ui::icon :name="$icon" class="size-4 shrink-0 text-muted" />
            @endif
            <input
                id="{{ $inputId }}"
                x-ref="input"
                x-model="query"
                x-on:input="typed()"
                x-on:focus="typed()"
                x-on:keydown.arrow-down.prevent="move(1)"
                x-on:keydown.arrow-up.prevent="move(-1)"
                x-on:keydown.enter="if (open && items().length) { $event.preventDefault(); chooseActive() }"
                x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); open = false }"
                x-on:keydown.tab="open = false"
                role="combobox"
                aria-autocomplete="list"
                aria-controls="{{ $inputId }}-listbox"
                x-bind:aria-expanded="open"
                autocomplete="off"
                @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $inputId }}-hint" @endif
                {{ $attributes->except(['id', 'class'])->merge(['type' => 'text'])->class(['min-w-0 flex-1 bg-transparent text-[15px] text-ink outline-none placeholder:text-muted']) }}
            />
        </div>

        <div
            id="{{ $inputId }}-listbox"
            role="listbox"
            @if ($label) aria-label="{{ $label }}" @endif
            x-show="open && items().length"
            x-cloak
            x-transition.opacity.duration.100ms
            class="absolute inset-x-0 top-full z-50 mt-1.5 flex max-h-80 flex-col overflow-y-auto rounded-[14px] border border-line bg-card p-1.5 shadow-[0_16px_40px_rgb(24_24_27/0.12)]"
        >
            {{ $slot }}
            @if ($empty)
                <span x-show="! hasResults()" class="px-2.5 py-3 text-[13px] text-muted">{{ $empty }}</span>
            @endif
        </div>
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$inputId.'-hint'" :message="$errorMessage" />
    @endif
</div>
