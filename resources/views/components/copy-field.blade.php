{{--
    Campo de copiar: chave de API, segredo de webhook, link de convite. Com masked, mostra só os últimos caracteres.

    <x-ui::copy-field label="Segredo do webhook do GitHub" value="whsec_9f3c1e7a2b8d4f60c5e1" masked description="Aparece inteiro só uma vez." />
    <x-ui::copy-field label="Link de convite" value="https://app.devaction.com.br/convite/8KQ2" />

    masked:  esconde o valor, deixando os visible últimos caracteres (padrão 4), com botão de mostrar
    prefix:  parte que fica sempre visível no começo (ex.: whsec_); padrão: até o primeiro "_"
--}}
@props([
    'label' => null,
    'description' => null,
    'value' => '',
    'masked' => false,
    'visible' => 4,
    'prefix' => null,
])

@php
    $value = (string) $value;
    $prefix ??= str_contains($value, '_') ? \Illuminate\Support\Str::before($value, '_').'_' : '';
    $hiddenLength = max(0, mb_strlen($value) - mb_strlen($prefix) - (int) $visible);
    $maskedValue = $prefix.str_repeat('•', min($hiddenLength, 16)).mb_substr($value, -1 * (int) $visible);
    $fieldId = $attributes->get('id') ?? 'ui-copy-'.substr(md5($label.$value), 0, 8);
@endphp

<div data-field data-copy-field @class(['flex min-w-0 flex-col gap-1.5', $attributes->get('class')])>
    @if ($label)
        <x-ui::label :for="$fieldId">{{ $label }}</x-ui::label>
    @endif

    <div
        x-data="{ revealed: false, copied: false, copy() { navigator.clipboard?.writeText(@js($value)); this.copied = true; setTimeout(() => this.copied = false, 1600) } }"
        class="flex h-10 w-full min-w-0 overflow-hidden rounded-[10px] border border-line"
    >
        <output
            id="{{ $fieldId }}"
            @if ($description) aria-describedby="{{ $fieldId }}-hint" @endif
            class="flex min-w-0 flex-1 items-center overflow-hidden bg-subtle px-3 font-mono text-[13px] whitespace-nowrap text-ink-2"
            @if ($masked) x-text="revealed ? @js($value) : @js($maskedValue)" @endif
        >{{ $masked ? $maskedValue : $value }}</output>
        @if ($masked)
            <button
                type="button"
                x-on:click="revealed = ! revealed"
                x-bind:aria-label="revealed ? 'Esconder' : 'Mostrar'"
                x-bind:aria-pressed="revealed.toString()"
                aria-label="Mostrar"
                class="flex w-10 shrink-0 cursor-pointer items-center justify-center border-l border-line bg-card text-ink-2 transition hover:text-ink"
            >
                <x-ui::icon name="view" class="size-4" x-show="! revealed" />
                <x-ui::icon name="view-off" class="size-4" x-show="revealed" x-cloak />
            </button>
        @endif
        <button
            type="button"
            x-on:click="copy()"
            x-bind:class="copied ? 'bg-ok-bg text-ok-fg' : 'bg-card text-ink'"
            class="flex shrink-0 cursor-pointer items-center gap-1.5 border-l border-line px-3 text-[13px] font-medium transition"
        >
            <x-ui::icon name="copy-01" class="size-3.5" x-show="! copied" />
            <x-ui::icon name="tick-02" class="size-3.5" x-show="copied" x-cloak />
            <span x-text="copied ? 'Copiado' : 'Copiar'">Copiar</span>
        </button>
        <span class="sr-only" aria-live="polite" x-text="copied ? 'Copiado para a área de transferência' : ''"></span>
    </div>

    @if ($description)
        <x-ui::description :id="$fieldId.'-hint'">{{ $description }}</x-ui::description>
    @endif
</div>
