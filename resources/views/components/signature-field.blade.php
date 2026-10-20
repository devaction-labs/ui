{{--
    Campo de assinatura: desenhar com o dedo/mouse ou digitar o nome. Exporta SVG.

    <x-ui::signature-field wire:model="signature" label="Assinatura do aditivo" />
    <x-ui::signature-field name="signature" mode="type" signer="Ana Souza" />

    wire:model / name: recebem o SVG da assinatura ('' quando vazia)
    mode:   draw (padrão) · type
    signer: nome já preenchido no modo digitar
    note:   texto pequeno embaixo (o que é guardado junto da assinatura)
--}}
@props([
    'label' => null,
    'error' => null,
    'mode' => 'draw',
    'signer' => '',
    'name' => null,
    'note' => null,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $fieldId = $attributes->get('id') ?? 'ui-signature-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5((string) $label), 0, 8));
@endphp

<div
    data-signature-field
    x-data="uiSignature({ mode: @js($mode), name: @js($signer) })"
    x-modelable="value"
    x-init="sync()"
    {{ $attributes->except('id')->class(['flex min-w-0 flex-col gap-3.5']) }}
>
    @if ($label)
        <span id="{{ $fieldId }}-label" class="text-sm font-medium text-ink">{{ $label }}</span>
    @endif

    @if ($name)
        <input type="hidden" name="{{ $name }}" x-bind:value="value" />
    @endif

    <div role="radiogroup" aria-label="Forma de assinar" class="flex rounded-[10px] border border-line bg-wrap p-[3px]">
        @foreach (['draw' => 'Desenhar', 'type' => 'Digitar'] as $modeKey => $modeLabel)
            <button
                type="button"
                role="radio"
                x-bind:aria-checked="mode === @js($modeKey)"
                x-on:click="setMode(@js($modeKey))"
                class="h-8 flex-1 cursor-pointer rounded-lg text-[13px] text-ink-3 transition"
                x-bind:class="mode === @js($modeKey) && 'bg-card text-ink! shadow-[0_1px_2px_rgb(24_24_27/0.12)]'"
            >{{ $modeLabel }}</button>
        @endforeach
    </div>

    <div x-show="mode === 'draw'" @if ($mode !== 'draw') x-cloak @endif>
        <div
            x-ref="pad"
            role="img"
            @if ($label) aria-labelledby="{{ $fieldId }}-label" @else aria-label="Área para desenhar a assinatura" @endif
            x-on:pointerdown="down($event)"
            x-on:pointermove="move($event)"
            x-on:pointerup="up()"
            x-on:pointercancel="up()"
            @class([
                'relative h-[170px] cursor-crosshair touch-none overflow-hidden rounded-xl border border-dashed bg-subtle',
                'border-missing' => $errorMessage,
                'border-muted' => ! $errorMessage,
            ])
        >
            <svg viewBox="0 0 600 200" preserveAspectRatio="none" class="absolute inset-0 size-full text-ink" aria-hidden="true">
                <line x1="40" y1="160" x2="560" y2="160" class="stroke-line-hover" stroke-width="1" vector-effect="non-scaling-stroke" />
                <template x-for="(d, index) in paths()" x-bind:key="index">
                    <path x-bind:d="d" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
                </template>
                <path x-ref="live" d="" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
            </svg>
            <span x-show="isEmpty()" class="pointer-events-none absolute inset-x-0 top-1/2 -translate-y-1/2 text-center text-[13px] text-muted">Assine aqui</span>
        </div>
    </div>

    <label x-show="mode === 'type'" @if ($mode !== 'type') x-cloak @endif class="flex flex-col gap-2 text-sm font-medium text-ink">
        Nome completo
        <input
            type="text"
            x-model="name"
            x-on:input="sync()"
            autocomplete="name"
            class="h-11 rounded-xl border border-line-strong bg-card px-3.5 text-[15px] font-normal text-ink outline-none focus:border-ink focus:ring-4 focus:ring-ink/10"
        />
        <span
            aria-hidden="true"
            class="flex h-[110px] items-end overflow-hidden rounded-xl border border-dashed border-muted bg-subtle px-5 pb-[18px] font-['Caveat','Segoe_Script',cursive] text-[40px] font-medium whitespace-nowrap text-ink"
            x-text="name"
        ></span>
    </label>

    <div class="flex items-center justify-between gap-2">
        <x-ui::button variant="ghost" size="sm" icon="eraser" x-on:click="clear()">Limpar</x-ui::button>
        <span class="text-xs text-muted" aria-live="polite" x-text="isEmpty() ? 'Assinatura vazia' : 'Pronto para assinar'">Assinatura vazia</span>
    </div>

    @if ($errorMessage)
        <x-ui::error :id="$fieldId.'-hint'" :message="$errorMessage" />
    @endif

    @if ($note)
        <span class="text-xs leading-normal text-muted">{{ $note }}</span>
    @endif
</div>
