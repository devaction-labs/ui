{{--
    Campo de dinheiro: formata enquanto digita e envia o número cru.

    <x-ui::money-input wire:model="amount" label="Valor do marco" suffix="por marco" />
    <x-ui::money-input name="price" currency="USD" locale="en-US" value="1250.5" />

    currency: BRL (padrão, mostra R$), USD, EUR ou o símbolo que quiser em symbol
    O wire:model e o name ficam no input escondido, que recebe o número com ponto (1234.56).
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'currency' => 'BRL',
    'symbol' => null,
    'locale' => 'pt-BR',
    'decimals' => 2,
    'suffix' => null,
    'placeholder' => null,
    'value' => null,
])

@php
    $symbol ??= match (strtoupper($currency)) {
        'USD' => 'US$',
        'EUR' => '€',
        default => 'R$',
    };

    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $attributes->get('name') ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $inputId = $attributes->get('id') ?? 'ui-money-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$suffix), 0, 8));
    $placeholder ??= number_format(0, (int) $decimals, ',', '.');

    $rawAttributes = $attributes->filter(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:') || str_starts_with($key, 'x-model') || $key === 'name');
    $visibleAttributes = $attributes->filter(fn ($attributeValue, string $key): bool => ! (str_starts_with($key, 'wire:') || str_starts_with($key, 'x-model') || $key === 'name' || $key === 'id'));
@endphp

<div data-field class="flex min-w-0 flex-col gap-1.5">
    @if ($label)
        <x-ui::label :for="$inputId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-money-input
        x-data="uiMoney({ locale: @js($locale), decimals: {{ (int) $decimals }}, value: @js($value) })"
        @class([
            'flex h-11 w-full items-stretch overflow-hidden rounded-xl border bg-card transition',
            'border-missing ring-4 ring-missing/10' => $errorMessage,
            'border-line-strong focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $errorMessage,
        ])
    >
        <span class="flex shrink-0 items-center border-r border-line-strong bg-wrap px-3 text-sm text-ink-3" aria-hidden="true">{{ $symbol }}</span>
        <input
            id="{{ $inputId }}"
            type="text"
            inputmode="numeric"
            autocomplete="off"
            x-bind:value="display"
            x-on:input="typed($event)"
            placeholder="{{ $placeholder }}"
            aria-label="{{ $label ? $label.' em '.$symbol : $symbol }}"
            @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $inputId }}-hint" @endif
            {{ $visibleAttributes->class(['min-w-0 flex-1 bg-transparent px-3 text-right text-[15px] text-ink tabular-nums outline-none placeholder:text-muted']) }}
        />
        <input type="hidden" x-ref="raw" value="{{ $value }}" {{ $rawAttributes }} />
        @if ($suffix)
            <span class="flex shrink-0 items-center border-l border-line-strong bg-wrap px-3 text-[13px] text-ink-3">{{ $suffix }}</span>
        @endif
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$inputId.'-hint'" :message="$errorMessage" />
    @endif
</div>
