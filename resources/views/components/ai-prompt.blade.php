{{--
    Composer de IA: pergunta com anexos, comandos e menções; cresce até 8 linhas.

    <x-ui::ai-prompt wire:model="prompt" submit="send" stop="cancel" busy-property="isResponding" max="4000">
        <x-slot:attachments>…</x-slot:attachments>
        <x-slot:toolbar><x-ui::badge color="warn" icon="sparkles">Revisor · rigoroso</x-ui::badge></x-slot:toolbar>
    </x-ui::ai-prompt>

    Enter envia · Shift+Enter quebra a linha · Esc para a resposta
    submit / stop:  métodos do Livewire chamados ao enviar (recebe o texto) e ao parar
    busy-property:  propriedade do Livewire que diz se a IA está respondendo (busy para valor fixo)
    Sem Livewire:   ouça ai-prompt-submit (detail.text) e ai-prompt-stop
    max:            limite de caracteres; acima dele o contador fica vermelho e enviar trava
    slots:          attachments (em cima do texto) · toolbar (à esquerda, depois dos botões)
--}}
@props([
    'placeholder' => 'Pergunte sobre o projeto…',
    'max' => null,
    'submit' => null,
    'stop' => null,
    'busy' => false,
    'busyProperty' => null,
    'attach' => true,
    'commands' => true,
    'mentions' => true,
    'label' => 'Mensagem para a IA',
])

@php
    $config = [
        'max' => $max === null ? null : (int) $max,
        'submit' => $submit,
        'stop' => $stop,
        'busy' => (bool) $busy,
        'busyProperty' => $busyProperty,
    ];
@endphp

<div
    data-ai-prompt
    x-data="uiPrompt(@js($config))"
    x-modelable="value"
    {{ $attributes->class(['w-full rounded-[18px] border bg-card transition']) }}
    x-bind:class="overLimit()
        ? 'border-missing shadow-[0_0_0_3px_rgb(185_28_28/0.1)]'
        : (value ? 'border-line-strong shadow-[0_0_0_3px_rgb(24_24_27/0.06),0_8px_24px_-16px_rgb(24_24_27/0.3)]' : 'border-line shadow-[0_8px_24px_-16px_rgb(24_24_27/0.3)]')"
>
    @isset($attachments)
        <div class="flex flex-wrap gap-2 px-3 pt-3">{{ $attachments }}</div>
    @endisset

    <div class="px-4 pt-3.5 pb-1.5">
        <textarea
            x-ref="input"
            x-model="value"
            x-on:keydown="keydown($event)"
            x-bind:readonly="isBusy()"
            rows="1"
            aria-label="{{ $label }}"
            placeholder="{{ $placeholder }}"
            @if ($max) x-bind:aria-invalid="overLimit()" @endif
            class="block min-h-[30px] w-full resize-none overflow-hidden bg-transparent text-[15px] leading-[1.55] text-ink outline-none placeholder:text-muted read-only:text-muted"
        ></textarea>
    </div>

    <div class="flex items-center gap-0.5 px-2.5 pt-2 pb-2.5">
        @if ($attach)
            <button type="button" x-on:click="$dispatch('ai-prompt-attach')" aria-label="Anexar" title="Anexar" class="flex size-[34px] cursor-pointer items-center justify-center rounded-[10px] text-ink-3 transition hover:bg-wrap hover:text-ink">
                <x-ui::icon name="attachment-01" class="size-[18px]" />
            </button>
        @endif
        @if ($commands)
            <button type="button" x-on:click="insert('/')" aria-label="Comandos" title="Comandos" class="flex size-[34px] cursor-pointer items-center justify-center rounded-[10px] text-[17px] text-ink-3 transition hover:bg-wrap hover:text-ink">/</button>
        @endif
        @if ($mentions)
            <button type="button" x-on:click="insert('@')" aria-label="Mencionar" title="Mencionar" class="flex size-[34px] cursor-pointer items-center justify-center rounded-[10px] text-ink-3 transition hover:bg-wrap hover:text-ink">
                <x-ui::icon name="at" class="size-[18px]" />
            </button>
        @endif
        @isset($toolbar)
            <div class="ml-1.5 flex min-w-0 items-center gap-1.5">{{ $toolbar }}</div>
        @endisset

        <div class="ml-auto flex items-center gap-2">
            @if ($max)
                <span class="font-mono text-[11px]" x-show="length()" x-bind:class="overLimit() ? 'text-risk-fg' : 'text-muted'" x-text="counter()" aria-live="polite"></span>
            @endif
            <button
                type="button"
                x-on:click="send()"
                x-bind:disabled="! isBusy() && ! canSend()"
                x-bind:aria-label="isBusy() ? 'Parar resposta' : 'Enviar'"
                aria-label="Enviar"
                class="flex size-[34px] cursor-pointer items-center justify-center rounded-[10px] bg-ink text-on-ink transition hover:opacity-90 disabled:cursor-not-allowed disabled:bg-line disabled:text-muted"
            >
                <x-ui::icon name="stop" class="size-4" x-show="isBusy()" x-cloak />
                <x-ui::icon name="arrow-up-02" class="size-4" x-show="! isBusy()" />
            </button>
        </div>
    </div>
</div>
