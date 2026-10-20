{{--
    Seleção nativa com o visual dos campos do kit. Com label/description vira um campo completo.

    <x-ui::select wire:model="stage" label="Etapa" placeholder="Escolha a etapa">
        <x-ui::select.option value="lead">Lead</x-ui::select.option>
        <x-ui::select.option value="proposta">Proposta</x-ui::select.option>
    </x-ui::select>

    size: sm (36) · base (44, padrão) · lg (52)
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'placeholder' => null,
    'size' => 'base',
    'invalid' => null,
    'bare' => false,
])

@php
    $modelName = collect($attributes->getAttributes())
        ->first(fn ($value, string $key): bool => str_starts_with($key, 'wire:model'));
    $fieldName = $attributes->get('name') ?? $modelName;
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($fieldName && $errorBag ? $errorBag->first($fieldName) : null);
    $invalid ??= filled($errorMessage);
    $hasField = ! $bare && ($label || $description || $errorMessage);
    $selectId = $attributes->get('id') ?? 'ui-select-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$placeholder), 0, 8));

    [$heightClasses, $paddingClasses, $radiusClasses, $textClasses] = match ($size) {
        'sm' => ['h-9', 'pr-9 pl-3', 'rounded-[10px]', 'text-[13px]'],
        'lg' => ['h-13', 'pr-11 pl-4', 'rounded-[14px]', 'text-base'],
        default => ['h-11', 'pr-10 pl-3.5', 'rounded-xl', 'text-[15px]'],
    };
@endphp

@if ($hasField)
    <x-ui::field>
        @if ($label)
            <x-ui::label :for="$selectId">{{ $label }}</x-ui::label>
        @endif
        <x-ui::select bare :placeholder="$placeholder" :size="$size" :invalid="$invalid" :attributes="$attributes->merge(['id' => $selectId, 'aria-describedby' => $selectId.'-hint'])">{{ $slot }}</x-ui::select>
        @if ($description && ! $errorMessage)
            <x-ui::description :id="$selectId.'-hint'">{{ $description }}</x-ui::description>
        @endif
        @if ($errorMessage)
            <x-ui::error :id="$selectId.'-hint'" :message="$errorMessage" />
        @endif
    </x-ui::field>
@else
    <div data-select class="relative flex w-full min-w-0">
        <select
            @if ($invalid) aria-invalid="true" @endif
            {{ $attributes->class([
                'w-full min-w-0 cursor-pointer appearance-none border bg-card text-ink outline-none transition disabled:cursor-not-allowed disabled:border-dashed disabled:bg-subtle disabled:text-muted',
                $heightClasses,
                $paddingClasses,
                $radiusClasses,
                $textClasses,
                'border-missing ring-4 ring-missing/10' => $invalid,
                'border-line-strong focus:border-ink focus:ring-4 focus:ring-ink/10' => ! $invalid,
            ]) }}
        >
            @if ($placeholder)
                <option value="" disabled @selected(! $attributes->get('value'))>{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
        <x-ui::icon name="arrow-down-01" class="pointer-events-none absolute top-1/2 right-3.5 size-4 -translate-y-1/2 text-muted" />
    </div>
@endif
