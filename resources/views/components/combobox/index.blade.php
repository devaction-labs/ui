{{--
    Combobox (select com busca): escolha numa lista longa, filtrando pelo texto.

    <x-ui::combobox wire:model.live="project" label="Projeto" placeholder="Escolha um projeto" search-placeholder="Filtrar projetos…">
        <x-ui::combobox.option value="igreja" color="#2563EB" meta="12 PRs">Igreja Viva</x-ui::combobox.option>
        <x-ui::combobox.option value="loja" color="#16A34A">Loja Ponto</x-ui::combobox.option>
    </x-ui::combobox>

    Várias opções: multiple (o valor vira um array e o painel fica aberto ao escolher)
    <x-ui::combobox wire:model.live="companyIds" multiple placeholder="Empresas">…</x-ui::combobox>

    Busca no servidor: :filter="false" desliga o filtro do navegador e o slot input liga o texto ao Livewire.
    Inclua sempre as opções já escolhidas na lista; os rótulos ficam guardados quando a lista muda.
    <x-ui::combobox wire:model="companyId" :filter="false">
        <x-slot:input><x-ui::combobox.input wire:model.live.debounce.250ms="companySearch" placeholder="Buscar empresa…" /></x-slot:input>
        @foreach ($this->companies as $company) <x-ui::combobox.option :value="$company->id">{{ $company->name }}</x-ui::combobox.option> @endforeach
    </x-ui::combobox>

    searchable: false esconde a caixa de busca (lista curta)
    wire:model / x-model: no grupo (x-modelable); name: vai para inputs escondidos (name[] com multiple)
    empty: texto quando a busca não encontra nada
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => null,
    'searchPlaceholder' => null,
    'empty' => null,
    'name' => null,
    'value' => null,
    'multiple' => false,
    'searchable' => true,
    'filter' => true,
])

@php
    $placeholder ??= __('Select…');
    $searchPlaceholder ??= __('Filter…');
    $empty ??= __('No results.');
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? ($errorBag->first($fieldName) ?: $errorBag->first($fieldName.'.*')) : null);
    $comboboxId = $attributes->get('id') ?? 'ui-combobox-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$placeholder), 0, 8));
    $initialValue = $multiple ? array_values(array_map('strval', (array) ($value ?? []))) : $value;
@endphp

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label)
        <x-ui::label :for="$comboboxId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-combobox
        @if ($multiple) data-multiple @endif
        x-data="uiCombobox({ value: @js($initialValue), multiple: @js((bool) $multiple), filter: @js((bool) $filter) })"
        x-modelable="value"
        x-on:click.outside="close()"
        x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); close(true) }"
        {{ $attributes->except(['id', 'class'])->class(['group/combobox relative w-full']) }}
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
            @if ($multiple)
                <span class="min-w-0 flex-1 truncate" x-show="selectedValues().length" x-text="selectedLabel"></span>
                <span class="shrink-0 rounded-md bg-wrap px-1.5 py-0.5 font-mono text-[11px] text-ink-2" x-show="selectedValues().length > 1" x-text="selectedValues().length"></span>
            @else
                <span class="flex min-w-0 items-center gap-2 truncate" x-show="selectedLabel" x-html="selectedHtml"></span>
            @endif
            <span class="min-w-0 flex-1 truncate text-muted" x-show="! selectedValues().length">{{ $placeholder }}</span>
            <x-ui::icon name="arrow-down-01" class="size-4 shrink-0 text-muted transition" x-bind:class="open && 'rotate-180'" />
        </button>

        @if ($multiple)
            @if ($name)
                <template x-for="item in value" x-bind:key="item">
                    <input type="hidden" name="{{ $name }}[]" x-bind:value="item" />
                </template>
            @endif
        @elseif ($name)
            <input type="hidden" x-ref="raw" name="{{ $name }}" value="{{ $value }}" />
        @else
            <input type="hidden" x-ref="raw" value="{{ $value }}" />
        @endif

        <div
            x-show="open"
            x-ui-float="{ match: true }"
            x-cloak
            x-transition.opacity.duration.100ms
            class="absolute inset-x-0 top-full z-50 mt-1.5 overflow-hidden rounded-[14px] border border-line bg-card shadow-[0_16px_40px_rgb(24_24_27/0.12)]"
        >
            @isset($input)
                {{ $input }}
            @elseif ($searchable)
                <x-ui::combobox.input :placeholder="$searchPlaceholder" :listbox="$comboboxId.'-listbox'" />
            @endisset
            <div id="{{ $comboboxId }}-listbox" role="listbox" @if ($multiple) aria-multiselectable="true" @endif @if ($label) aria-label="{{ $label }}" @endif class="flex max-h-60 flex-col overflow-y-auto p-1.5" data-combobox-id="{{ $comboboxId }}">
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
        <x-ui::error :id="$comboboxId.'-hint'" :message="$errorMessage" />
    @endif
</div>
