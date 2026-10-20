{{--
    Toast de progresso dentro da página (sem a pilha): o fundo preenche conforme avança.
    Para o toast flutuante, use DevActionUI.progressToast() ou $this->progressToast().

    <x-ui::toast.progress :value="$percent" heading="Importando contatos" text="1.240 de 3.100" />
--}}
@props([
    'value' => 0,
    'heading' => null,
    'text' => null,
])

@php
    $value = (int) min(100, max(0, (int) $value));
    $isDone = $value >= 100;
@endphp

<div
    data-toast-progress
    role="status"
    aria-live="polite"
    {{ $attributes->class([
        'relative flex items-center gap-3 overflow-hidden rounded-[14px] border bg-card px-4 py-3.5 text-sm text-ink shadow-[0_12px_30px_rgb(24_24_27/0.10)]',
        $isDone ? 'border-ok-line' : 'border-line',
    ]) }}
>
    <span aria-hidden="true" @class(['absolute inset-y-0 left-0 transition-[width] duration-150 ease-linear motion-reduce:transition-none', $isDone ? 'bg-ok-fg/15' : 'bg-ai/15']) style="width: {{ $value }}%"></span>

    <span @class(['relative flex size-[34px] shrink-0 items-center justify-center rounded-[10px]', $isDone ? 'bg-ok-bg text-ok-fg' : 'bg-warn-bg text-warn-fg'])>
        @if ($isDone)
            <x-ui::icon name="tick-02" stroke-width="2" class="size-[18px]" />
        @else
            <x-ui::spinner size="sm" color="ai" />
        @endif
    </span>

    <span class="relative flex min-w-0 flex-1 flex-col gap-0.5">
        @if ($heading)
            <strong class="font-semibold">{{ $heading }}</strong>
        @endif
        @if ($text)
            <span class="text-ink-3">{{ $text }}</span>
        @endif
        {{ $slot }}
    </span>

    <span
        role="progressbar"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-valuenow="{{ $value }}"
        @if ($heading) aria-label="{{ $heading }}" @endif
        @class(['relative font-mono text-[13px] font-medium tabular-nums', $isDone ? 'text-ok-fg' : 'text-warn-fg'])
    >{{ $value }}%</span>
</div>
