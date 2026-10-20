{{--
    Campo de texto (API no estilo Flux). Com label/description vira um campo completo.

    <x-ui::input wire:model="name" label="Nome" icon="user" />
    <x-ui::input type="email" wire:model.blur="email" label="E-mail" icon="at" description="Usamos para avisos de review." />
    <x-ui::input type="password" label="Senha" viewable />
    <x-ui::input value="whsec_8f2c…a91d" readonly copyable />
    <x-ui::input placeholder="Buscar" icon="search-01" kbd="⌘K" clearable size="sm" />

    size:      sm (36) · base (44, padrão) · lg (52)
    invalid:   força o estado de erro; com wire:model o erro vem da validação pelo nome do campo
    clearable: botão para limpar · copyable: botão para copiar · viewable: mostrar/ocultar senha
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'icon' => null,
    'iconTrailing' => null,
    'kbd' => null,
    'size' => 'base',
    'invalid' => null,
    'bare' => false,
    'clearable' => false,
    'copyable' => false,
    'viewable' => false,
    'prefix' => null,
    'suffix' => null,
])

@php
    $iconTrailing ??= $attributes->get('icon:trailing');
    $attributes = $attributes->except(['icon:trailing']);

    $modelName = collect($attributes->getAttributes())
        ->first(fn ($value, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $attributes->get('name') ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $invalid ??= filled($errorMessage);

    $isDisabled = (bool) $attributes->get('disabled');
    $isReadonly = (bool) $attributes->get('readonly');
    $hasField = ! $bare && ($label || $description || $errorMessage);

    [$heightClasses, $paddingClasses, $radiusClasses, $textClasses, $iconClasses] = match ($size) {
        'sm' => ['h-9', 'px-3', 'rounded-[10px]', 'text-[13px]', 'size-4'],
        'lg' => ['h-13', 'px-4', 'rounded-[14px]', 'text-base', 'size-5'],
        default => ['h-11', 'px-3.5', 'rounded-xl', 'text-[15px]', 'size-[18px]'],
    };

    $needsAlpine = $clearable || $copyable || $viewable;

    $inputId = $attributes->get('id') ?? 'ui-input-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$description.$attributes->get('placeholder')), 0, 8));
@endphp

@if ($hasField)
    <x-ui::field :class="$attributes->get('class')">
        @if ($label)
            <x-ui::label :for="$inputId">{{ $label }}</x-ui::label>
        @endif
        <x-ui::input
            bare
            :icon="$icon"
            :icon-trailing="$iconTrailing"
            :kbd="$kbd"
            :size="$size"
            :invalid="$invalid"
            :clearable="$clearable"
            :copyable="$copyable"
            :viewable="$viewable"
            :prefix="$prefix"
            :suffix="$suffix"
            :attributes="$attributes->except('class')->merge(['id' => $inputId, 'aria-describedby' => $inputId.'-hint'])"
        />
        @if ($description && ! $errorMessage)
            <x-ui::description :id="$inputId.'-hint'">{{ $description }}</x-ui::description>
        @endif
        @if ($errorMessage)
            <x-ui::error :id="$inputId.'-hint'" :message="$errorMessage" />
        @endif
    </x-ui::field>
@else
<div
    data-input
    @if ($needsAlpine) x-data="{ revealed: false, copied: false }" @endif
    @class([
        'flex w-full min-w-0 items-center gap-2.5 border transition',
        'has-[input:placeholder-shown]:[&_[data-input-clear]]:hidden' => $clearable,
        $attributes->get('class'),
        $heightClasses,
        $paddingClasses,
        $radiusClasses,
        'border-dashed border-line-strong bg-subtle text-muted' => $isDisabled,
        'border-line bg-subtle' => $isReadonly && ! $isDisabled,
        'border-missing ring-4 ring-missing/10' => $invalid && ! $isDisabled,
        'border-line-strong bg-card focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $invalid && ! $isDisabled && ! $isReadonly,
    ])
>
    @if ($prefix)
        <span class="shrink-0 text-muted {{ $textClasses }}">{{ $prefix }}</span>
    @endif
    @if ($icon)
        <x-ui::icon :name="$icon" @class([$iconClasses, 'text-missing' => $invalid, 'text-muted' => ! $invalid]) />
    @endif

    <input
        x-ref="input"
        @if ($viewable) x-bind:type="revealed ? 'text' : 'password'" @endif
        @if ($invalid) aria-invalid="true" @endif
        {{ $attributes->except('class')->merge(['type' => 'text'])->merge($clearable && ! $attributes->has('placeholder') ? ['placeholder' => "\u{00A0}"] : [])->class([
            'h-full min-w-0 flex-1 bg-transparent text-ink outline-none placeholder:text-muted disabled:cursor-not-allowed',
            $textClasses,
            'font-mono text-[13px] text-ink-2' => $isReadonly,
        ]) }}
    />

    @if ($suffix)
        <span class="shrink-0 text-muted {{ $textClasses }}">{{ $suffix }}</span>
    @endif
    @if ($kbd)
        <x-ui::kbd>{{ $kbd }}</x-ui::kbd>
    @endif
    @if ($clearable)
        <button type="button" aria-label="{{ __('Clear') }}" data-input-clear x-on:click="$refs.input.value = ''; $refs.input.dispatchEvent(new Event('input', { bubbles: true })); $refs.input.focus()" class="-mr-1.5 inline-flex size-7 shrink-0 cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-wrap hover:text-ink">
            <x-ui::icon name="cancel-01" class="size-4" />
        </button>
    @endif
    @if ($viewable)
        <button type="button" x-on:click="revealed = ! revealed" x-bind:aria-label="revealed ? @js(__('Hide password')) : @js(__('Show password'))" aria-label="{{ __('Show password') }}" class="-mr-1.5 inline-flex size-7 shrink-0 cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-wrap hover:text-ink">
            <x-ui::icon name="view" class="size-4" x-show="! revealed" />
            <x-ui::icon name="view-off" class="size-4" x-show="revealed" x-cloak />
        </button>
    @endif
    @if ($copyable)
        <button type="button" x-on:click="navigator.clipboard?.writeText($refs.input.value); copied = true; setTimeout(() => copied = false, 1500)" x-bind:aria-label="copied ? @js(__('Copied')) : @js(__('Copy'))" aria-label="{{ __('Copy') }}" class="-mr-2 inline-flex size-8 shrink-0 cursor-pointer items-center justify-center rounded-lg text-muted transition hover:bg-card hover:text-ink">
            <x-ui::icon name="copy-01" class="size-4" x-show="! copied" />
            <x-ui::icon name="tick-02" class="size-4 text-ok-fg" x-show="copied" x-cloak />
        </button>
    @endif
    @if ($iconTrailing)
        <x-ui::icon :name="$iconTrailing" :class="$iconClasses.' text-muted'" />
    @endif
</div>

@endif
