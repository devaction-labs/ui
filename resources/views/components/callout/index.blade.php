{{--
    Callout (API no estilo Flux): destaca uma informação e guia para a próxima ação.

    <x-ui::callout icon="clock-01" heading="Marco vence sexta" text="3 itens ainda sem PR no M3." />

    <x-ui::callout variant="danger" icon="alert-02">
        <x-ui::callout.heading>Por que precisa de você</x-ui::callout.heading>
        <x-ui::callout.text>O PR #145 está fora do escopo do M3.</x-ui::callout.text>
        <x-slot:actions>
            <x-ui::button size="sm">Rascunhar aditivo</x-ui::button>
            <x-ui::button size="sm" variant="ghost">Ignorar</x-ui::button>
        </x-slot:actions>
    </x-ui::callout>

    <x-ui::callout variant="ai" icon="sparkles" label="IA" heading="Resumo da reunião" text="…" stacked />

    variant: secondary (padrão) · success · warning · danger · ai · info
    inline:  ações à direita, na mesma linha
    stacked: card vertical com o ícone num chip (grade de callouts do design)
    label:   rótulo em mono maiúsculo acima do título
    slots:   icon · actions · controls (canto superior direito, ex.: fechar)
--}}
@props([
    'variant' => 'secondary',
    'icon' => null,
    'heading' => null,
    'text' => null,
    'label' => null,
    'inline' => false,
    'stacked' => false,
    'actions' => null,
    'controls' => null,
])

@php
    $toneClasses = match ($variant) {
        'success' => 'border-ok-line bg-ok-bg text-ok-fg',
        'warning', 'ai' => 'border-warn-line bg-warn-bg text-warn-fg',
        'danger' => 'border-risk-line bg-risk-bg text-risk-fg',
        'info' => 'border-info-line bg-info-bg text-info-fg',
        default => 'border-line bg-subtle text-ink-2',
    };

    $hasActions = $actions && $actions->isNotEmpty();
    $hasControls = $controls && $controls->isNotEmpty();
    $iconIsSlot = $icon instanceof \Illuminate\View\ComponentSlot;
@endphp

<div
    data-callout
    {{ $attributes->class([
        'relative flex border',
        $toneClasses,
        'flex-col gap-2.5 rounded-2xl p-[18px]' => $stacked,
        'gap-3 rounded-[14px] px-4 py-3.5' => ! $stacked,
    ]) }}
>
    @if ($icon)
        <span @class([
            'flex shrink-0 items-center justify-center',
            'size-8 rounded-[9px] bg-current/10' => $stacked,
            'mt-0.5' => ! $stacked,
        ])>
            @if ($iconIsSlot)
                {{ $icon }}
            @else
                <x-ui::icon :name="$icon" :class="$stacked ? 'size-4' : 'size-4.5'" />
            @endif
        </span>
    @endif

    <div class="flex min-w-0 flex-1 flex-col gap-0.5 text-sm leading-[1.45]">
        @if ($label)
            <span class="mb-1 font-mono text-[11px] tracking-[.08em] uppercase">{{ $label }}</span>
        @endif

        @if ($heading)
            <x-ui::callout.heading>{{ $heading }}</x-ui::callout.heading>
        @endif

        @if ($text)
            <x-ui::callout.text>{{ $text }}</x-ui::callout.text>
        @endif

        {{ $slot }}

        @if ($hasActions && ! $inline)
            <div class="mt-2.5 flex flex-wrap gap-2">{{ $actions }}</div>
        @endif
    </div>

    @if ($hasActions && $inline)
        <div class="flex shrink-0 flex-wrap items-center gap-2 self-center">{{ $actions }}</div>
    @endif

    @if ($hasControls)
        <div @class(['flex shrink-0 items-start gap-1', 'absolute top-2.5 right-2.5' => $stacked, '-my-1.5 -mr-2' => ! $stacked])>{{ $controls }}</div>
    @endif
</div>
