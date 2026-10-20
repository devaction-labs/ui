{{--
    Código de verificação (OTP): uma casa por dígito, colar preenche tudo, Backspace volta uma casa.

    <x-ui::otp-input wire:model="code" submit="verify" resend="sendCode" />
    <x-ui::otp-input length="4" mode="alphanumeric" label="Código do convite" />

    length:   quantidade de casas (padrão 6); separator junta em dois grupos (padrão com 6)
    mode:     numeric (padrão, teclado numérico) · alphanumeric
    submit:   método do Livewire chamado com o código quando a última casa é preenchida; sem ele, dispara otp-complete
    resend:   método do Livewire do "Reenviar"; cooldown em segundos (padrão 60) antes de poder reenviar
    success:  casas em verde (ex.: depois de verificar) · error/wire:model mostram o erro abaixo
--}}
@props([
    'length' => 6,
    'mode' => 'numeric',
    'separator' => null,
    'label' => null,
    'error' => null,
    'success' => null,
    'submit' => null,
    'resend' => null,
    'cooldown' => 60,
    'value' => '',
])

@php
    $length = max(1, (int) $length);
    $separator ??= $length === 6;
    $modelName = collect($attributes->getAttributes())->first(fn ($attributeValue, string $key): bool => str_starts_with($key, 'wire:model'));
    $errorBag = \Illuminate\Support\Facades\View::shared('errors');
    $errorMessage = $error ?? ($modelName && $errorBag ? $errorBag->first($modelName) : null);
    $groupId = $attributes->get('id') ?? 'ui-otp-'.($modelName ? \Illuminate\Support\Str::slug($modelName) : substr(md5((string) $label), 0, 8));
@endphp

<div
    data-otp-input
    x-data="uiOtp({ length: {{ $length }}, numeric: @js($mode !== 'alphanumeric'), value: @js((string) $value), submit: @js($submit), resend: @js($resend), cooldown: {{ (int) $cooldown }} })"
    x-modelable="value"
    {{ $attributes->except(['id'])->class(['flex flex-col items-center gap-3.5']) }}
>
    @if ($label)
        <span id="{{ $groupId }}-label" class="text-sm font-medium text-ink">{{ $label }}</span>
    @endif

    <div role="group" @if ($label) aria-labelledby="{{ $groupId }}-label" @else aria-label="{{ __('Verification code') }}" @endif class="flex items-center justify-center gap-2">
        @for ($index = 0; $index < $length; $index++)
            @if ($separator && $index === intdiv($length, 2))
                <span class="h-0.5 w-2.5 bg-line-hover" aria-hidden="true"></span>
            @endif
            <input
                type="text"
                @if ($index === 0) id="{{ $groupId }}" autocomplete="one-time-code" @else autocomplete="off" @endif
                inputmode="{{ $mode === 'alphanumeric' ? 'text' : 'numeric' }}"
                maxlength="{{ $length }}"
                aria-label="{{ __('Digit :position of :total', ['position' => $index + 1, 'total' => $length]) }}"
                @if ($errorMessage) aria-invalid="true" aria-describedby="{{ $groupId }}-hint" @endif
                x-bind:value="digits[{{ $index }}] ?? ''"
                x-on:input="typed($event, {{ $index }})"
                x-on:keydown.backspace="back($event, {{ $index }})"
                x-on:keydown.arrow-left.prevent="focusAt({{ $index - 1 }})"
                x-on:keydown.arrow-right.prevent="focusAt({{ $index + 1 }})"
                x-on:paste.prevent="paste($event)"
                x-on:focus="$event.target.select()"
                @class([
                    'size-12 h-[58px] rounded-xl border-[1.5px] bg-card text-center font-mono text-2xl font-medium caret-ink outline-none transition focus:ring-4',
                    'border-missing text-risk-fg focus:ring-missing/10' => $errorMessage,
                    'border-ok-fg text-ok-fg focus:ring-ok-fg/10' => $success && ! $errorMessage,
                    'border-line-strong text-ink focus:border-ink focus:ring-ink/10' => ! $errorMessage && ! $success,
                ])
            />
        @endfor
    </div>

    @if ($errorMessage)
        <p id="{{ $groupId }}-hint" role="alert" class="flex items-center gap-1.5 text-[13px] text-risk-fg"><x-ui::icon name="alert-02" class="size-3.5" />{{ $errorMessage }}</p>
    @elseif ($success)
        <p role="status" class="flex items-center gap-1.5 text-[13px] text-ok-fg"><x-ui::icon name="checkmark-circle-02" class="size-3.5" />{{ is_string($success) ? $success : __('Verified.') }}</p>
    @else
        <p class="min-h-5 text-[13px] text-muted" aria-live="polite" x-text="remaining() > 0 && filled() > 0 ? (remaining() === 1 ? @js(__('1 digit left')) : @js(__(':count digits left')).replace(':count', remaining())) : ''"></p>
    @endif

    @if ($resend)
        <p class="text-[13px] text-muted">
            {{ __("Didn't get it?") }}
            <button type="button" x-on:click="sendAgain()" x-bind:disabled="wait > 0" class="cursor-pointer font-medium text-ink underline-offset-4 hover:underline disabled:cursor-default disabled:text-muted disabled:no-underline">
                <span x-text="wait > 0 ? @js(__('Resend in :time')).replace(':time', clock()) : @js(__('Resend code'))">{{ __('Resend code') }}</span>
            </button>
        </p>
    @endif
</div>
