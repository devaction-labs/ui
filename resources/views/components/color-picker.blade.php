{{--
    Seletor de cor: arraste, escolha uma cor pronta ou digite. Sempre sai em hex.

    <x-ui::color-picker wire:model.live="color" label="Cor do projeto" />
    <x-ui::color-picker name="brand" value="#C2850F" alpha :presets="['#18181B', '#2563EB']" />
    <x-ui::color-picker inline value="#7C3AED" />

    alpha:   mostra o trilho de opacidade (#RRGGBBAA)
    presets: cores prontas; [] esconde
    inline:  só o painel, sem campo
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'value' => '#18181B',
    'alpha' => false,
    'presets' => ['#18181B', '#2563EB', '#7C3AED', '#DB2777', '#DC2626', '#EA580C', '#C2850F', '#16A34A'],
    'inline' => false,
    'name' => null,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $name ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $pickerId = $attributes->get('id') ?? 'ui-color-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$value), 0, 8));
@endphp

<div data-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label && ! $inline)
        <x-ui::label :for="$pickerId">{{ $label }}</x-ui::label>
    @endif

    <div
        data-color-picker
        x-data="uiColorPicker({ value: @js($value), alpha: @js((bool) $alpha) })"
        x-modelable="value"
        @unless ($inline)
            x-on:click.outside="open = false"
            x-on:keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); open = false; $refs.swatch.focus() }"
        @endunless
        {{ $attributes->except(['id', 'class'])->class(['relative w-full']) }}
    >
        @if ($name)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}" x-bind:value="value" />
        @endif

        @unless ($inline)
            <div @class([
                'flex h-11 w-full items-center gap-2.5 rounded-xl border bg-card px-3 transition',
                'border-missing ring-4 ring-missing/10' => $errorMessage,
                'border-line-strong focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $errorMessage,
            ])>
                <button
                    type="button"
                    x-ref="swatch"
                    x-on:click="open = ! open"
                    aria-haspopup="dialog"
                    x-bind:aria-expanded="open"
                    aria-label="Abrir seletor de cor"
                    class="size-[22px] shrink-0 cursor-pointer rounded-md shadow-[inset_0_0_0_1px_rgb(0_0_0/0.12)] outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    style="background: {{ $value }}"
                    x-bind:style="{ background: hex() }"
                ></button>
                <input
                    id="{{ $pickerId }}"
                    type="text"
                    x-model="text"
                    x-on:input="typed()"
                    x-on:blur="blurred()"
                    spellcheck="false"
                    autocomplete="off"
                    maxlength="9"
                    value="{{ $value }}"
                    @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $pickerId }}-hint" @endif
                    class="min-w-0 flex-1 bg-transparent font-mono text-sm text-ink uppercase outline-none"
                />
            </div>
        @endunless

        <div
            role="dialog"
            aria-label="Seletor de cor"
            @unless ($inline)
                x-show="open"
                x-cloak
                x-transition.opacity.duration.100ms
            @endunless
            @class([
                'flex w-[300px] max-w-full flex-col gap-3 rounded-[14px] border border-line bg-card p-3',
                'absolute top-full left-0 z-50 mt-1.5 shadow-[0_16px_40px_rgb(24_24_27/0.12)]' => ! $inline,
            ])
        >
            <div
                x-ref="area"
                role="slider"
                tabindex="0"
                aria-label="Saturação e brilho"
                x-bind:aria-valuetext="hex()"
                x-on:pointerdown="startDrag($event)"
                x-on:pointermove="drag($event)"
                x-on:pointerup="dragging = false"
                x-on:keydown.arrow-left.prevent="areaKey(-0.02, 0)"
                x-on:keydown.arrow-right.prevent="areaKey(0.02, 0)"
                x-on:keydown.arrow-up.prevent="areaKey(0, 0.02)"
                x-on:keydown.arrow-down.prevent="areaKey(0, -0.02)"
                class="relative h-[170px] cursor-crosshair touch-none rounded-[10px] outline-none focus-visible:ring-2 focus-visible:ring-ring"
                x-bind:style="{ background: `linear-gradient(to top, #000, transparent), linear-gradient(to right, #fff, ${hueColor()})` }"
            >
                <span
                    class="pointer-events-none absolute -mt-2 -ml-2 size-4 rounded-full border-2 border-white shadow-[0_0_0_1px_rgb(0_0_0/0.3)]"
                    x-bind:style="{ left: `${saturation * 100}%`, top: `${(1 - brightness) * 100}%` }"
                ></span>
            </div>

            <label class="flex">
                <span class="sr-only">Matiz</span>
                <input
                    type="range"
                    min="0"
                    max="360"
                    class="ui-color-range"
                    x-model.number="hue"
                    x-on:input="sync()"
                    style="--track: linear-gradient(90deg, #f00, #ff0, #0f0, #0ff, #00f, #f0f, #f00)"
                    x-bind:style="{ '--thumb': hueColor() }"
                />
            </label>

            @if ($alpha)
                <label class="flex">
                    <span class="sr-only">Opacidade</span>
                    <input
                        type="range"
                        min="0"
                        max="100"
                        class="ui-color-range"
                        x-model.number="opacity"
                        x-on:input="sync()"
                        x-bind:style="{ '--track': `linear-gradient(90deg, transparent, ${solid()}), repeating-conic-gradient(#e4e4e7 0 25%, #fff 0 50%) 0 0 / 10px 10px`, '--thumb': solid() }"
                    />
                </label>
            @endif

            <div class="flex h-[38px] items-center gap-2 rounded-[10px] border border-line pr-1.5 pl-2.5">
                <span class="flex-1 font-mono text-[13px] text-ink" x-text="hex()"></span>
                <button
                    type="button"
                    x-show="canPickFromScreen()"
                    x-on:click="pickFromScreen()"
                    aria-label="Conta-gotas"
                    class="flex size-[30px] cursor-pointer items-center justify-center rounded-lg text-ink-3 transition hover:bg-wrap hover:text-ink"
                >
                    <x-ui::icon name="pen-01" class="size-4" />
                </button>
            </div>

            @if ($presets !== [])
                <div role="radiogroup" aria-label="Cores prontas" class="grid grid-cols-8 gap-1.5">
                    @foreach ($presets as $preset)
                        <button
                            type="button"
                            role="radio"
                            aria-label="{{ $preset }}"
                            x-bind:aria-checked="isCurrent(@js($preset))"
                            x-on:click="choose(@js($preset))"
                            class="aspect-square cursor-pointer rounded-[7px] outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            style="background: {{ $preset }}"
                            x-bind:class="isCurrent(@js($preset)) && 'ring-2 ring-ink ring-offset-2 ring-offset-card'"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if ($description && ! $errorMessage)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif
    @if ($errorMessage)
        <x-ui::error :id="$pickerId.'-hint'" :message="$errorMessage" />
    @endif
</div>
