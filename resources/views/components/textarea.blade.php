{{--
    Área de texto (API no estilo Flux), com contador, rodapé de ações e crescimento automático.

    <x-ui::textarea wire:model="scope" label="Escopo do marco" maxlength="500" rows="5" />
    <x-ui::textarea rows="auto" placeholder="Escreva uma nota…">
        <x-slot:footer>
            <x-ui::button variant="ghost" size="sm" icon="attachment-01" aria-label="Anexar" />
            <x-ui::button variant="ai" size="sm" icon="sparkles">Melhorar com IA</x-ui::button>
        </x-slot:footer>
    </x-ui::textarea>

    rows:   número de linhas; auto cresce com o texto (field-sizing-content)
    resize: vertical (padrão) · horizontal · both · none
--}}
@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'invalid' => null,
    'rows' => 4,
    'resize' => 'vertical',
    'footer' => null,
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
    $textareaId = $attributes->get('id') ?? 'ui-textarea-'.($fieldName ? \Illuminate\Support\Str::slug($fieldName) : substr(md5($label.$attributes->get('placeholder')), 0, 8));

    $maxLength = $attributes->get('maxlength');
    $hasFooter = ($footer && $footer->isNotEmpty()) || $maxLength;
    $isAuto = $rows === 'auto';

    $resizeClasses = match ($resize) {
        'none' => 'resize-none',
        'horizontal' => 'resize-x',
        'both' => 'resize',
        default => 'resize-y',
    };
@endphp

@if ($hasField)
    <x-ui::field>
        @if ($label)
            <x-ui::label :for="$textareaId">{{ $label }}</x-ui::label>
        @endif
        <x-ui::textarea
            bare
            :rows="$rows"
            :resize="$resize"
            :invalid="$invalid"
            :footer="$footer"
            :attributes="$attributes->merge(['id' => $textareaId, 'aria-describedby' => $textareaId.'-hint'])"
        >{{ $slot }}</x-ui::textarea>
        @if ($description && ! $errorMessage)
            <x-ui::description :id="$textareaId.'-hint'">{{ $description }}</x-ui::description>
        @endif
        @if ($errorMessage)
            <x-ui::error :id="$textareaId.'-hint'" :message="$errorMessage" />
        @endif
    </x-ui::field>
@else
    <div
        data-textarea
        @if ($maxLength) x-data="{ count: 0, max: {{ (int) $maxLength }} }" x-init="count = $refs.textarea.value.length" @endif
        @class([
            'flex w-full min-w-0 flex-col overflow-hidden rounded-[14px] border transition',
            'border-missing ring-4 ring-missing/10' => $invalid,
            'border-line-strong bg-card focus-within:border-ink focus-within:ring-4 focus-within:ring-ink/10' => ! $invalid,
        ])
    >
        <textarea
            x-ref="textarea"
            @if ($maxLength) x-on:input="count = $el.value.length" @endif
            @if ($invalid) aria-invalid="true" @endif
            @unless ($isAuto) rows="{{ (int) $rows }}" @endunless
            {{ $attributes->class([
                'block w-full min-w-0 bg-transparent px-3.5 py-3 text-[15px] leading-[1.55] text-ink outline-none placeholder:text-muted disabled:cursor-not-allowed disabled:bg-subtle',
                $resizeClasses,
                'field-sizing-content min-h-[4.5rem] max-h-[19rem]' => $isAuto,
            ]) }}
        >{{ trim((string) $slot) }}</textarea>

        @if ($hasFooter)
            <div class="flex min-h-[46px] items-center gap-1 border-t border-line-soft bg-subtle py-1.5 pr-2 pl-2.5">
                {{ $footer }}
                @if ($maxLength)
                    <span
                        class="ml-auto font-mono text-xs tabular-nums"
                        x-bind:class="count >= max * 0.9 ? 'text-risk-fg' : 'text-muted'"
                        aria-live="polite"
                    ><span x-text="count">0</span>/{{ (int) $maxLength }}</span>
                @endif
            </div>
        @endif
    </div>
@endif
