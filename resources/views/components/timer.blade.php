{{--
    Timer do banco de horas: conta o tempo de uma tarefa e avisa ao parar.

    <x-ui::timer label="Corrigir N+1 em contribuições · SGE" x-on:timer-stopped="$wire.logHours($event.detail.seconds)" />
    <x-ui::timer variant="mini" label="Mini timer" :seconds="1520" running />

    seconds:    tempo já contado
    running:    começa rodando; com started-at (timestamp unix) retoma o tempo certo depois de recarregar
    variant:    card (padrão) · mini (linha para barras e listas)
    Eventos:    timer-started, timer-paused e timer-stopped com detail.seconds
    wire:model: recebe os segundos a cada tique
--}}
@props([
    'label' => null,
    'seconds' => 0,
    'running' => false,
    'startedAt' => null,
    'variant' => 'card',
])

@php
    $initial = sprintf('%02d:%02d:%02d', intdiv((int) $seconds, 3600), intdiv((int) $seconds % 3600, 60), (int) $seconds % 60);
    $alpineConfig = ['seconds' => (int) $seconds, 'running' => (bool) $running, 'startedAt' => $startedAt];
@endphp

@if ($variant === 'mini')
    <div
        data-timer
        x-data="uiTimer(@js($alpineConfig))"
        x-modelable="value"
        {{ $attributes->class(['flex items-center gap-2.5 rounded-xl border border-line bg-card py-2.5 pr-1.5 pl-3']) }}
    >
        <span class="size-2 shrink-0 rounded-full bg-line-hover" x-bind:class="running && 'bg-ok-fg! animate-pulse motion-reduce:animate-none'" aria-hidden="true"></span>
        <span class="min-w-0 flex-1 truncate text-[13px] text-ink-2">{{ $label }}</span>
        <span class="font-mono text-[13px] text-ink tabular-nums" role="timer" x-text="format(false)">{{ substr($initial, 0, 5) }}</span>
        <button type="button" x-on:click="toggle()" x-bind:aria-label="running ? 'Pausar' : 'Iniciar'" class="flex size-7 cursor-pointer items-center justify-center rounded-lg text-ink-2 transition hover:bg-wrap hover:text-ink">
            <x-ui::icon name="pause" class="size-3.5" x-show="running" />
            <x-ui::icon name="play" class="size-3.5" x-show="! running" />
        </button>
    </div>
@else
    <div
        data-timer
        x-data="uiTimer(@js($alpineConfig))"
        x-modelable="value"
        {{ $attributes->class(['flex flex-col items-center gap-1.5 rounded-2xl border border-line bg-subtle p-[18px] transition-colors']) }}
        x-bind:class="running && 'border-ok-line! bg-ok-bg!'"
    >
        <span class="font-mono text-[11px] tracking-[0.08em] text-muted" x-bind:class="running && 'text-ok-fg!'" x-text="running ? 'RODANDO' : 'PAUSADO'">{{ $running ? 'RODANDO' : 'PAUSADO' }}</span>
        <span class="font-mono text-[44px] leading-tight font-medium tracking-[-0.02em] text-ink tabular-nums" role="timer" aria-live="off" x-text="format()">{{ $initial }}</span>
        @if ($label)
            <span class="text-center text-[13px] text-ink-3">{{ $label }}</span>
        @endif
        <span class="mt-2 flex gap-2">
            <button
                type="button"
                x-on:click="toggle()"
                x-bind:aria-label="running ? 'Pausar' : 'Iniciar'"
                class="flex size-[52px] cursor-pointer items-center justify-center rounded-full bg-ok-fg text-white transition hover:opacity-90"
                x-bind:class="running && 'bg-ink! text-on-ink!'"
            >
                <x-ui::icon name="pause" class="size-5" x-show="running" />
                <x-ui::icon name="play" class="size-5" x-show="! running" />
            </button>
            <button type="button" x-on:click="stop()" aria-label="Parar e lançar" class="flex size-[52px] cursor-pointer items-center justify-center rounded-full border border-line-strong bg-card text-ink transition hover:bg-subtle">
                <x-ui::icon name="stop" class="size-5" />
            </button>
        </span>
        {{ $slot }}
    </div>
@endif
