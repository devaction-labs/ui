{{--
    Estado vazio: diz o que é a área, por que está vazia e qual o próximo passo.

    <x-ui::empty-state icon="folder-add" label="Projetos" heading="Seu primeiro projeto começa aqui"
        text="Projeto junta contrato, escopo por marcos, tarefas e os PRs que entregam cada item.">
        <x-slot:actions>
            <x-ui::button variant="primary" icon="add-01">Criar projeto</x-ui::button>
        </x-slot:actions>
    </x-ui::empty-state>

    <x-ui::empty-state variant="dashed" icon="search-01" heading="Nada para “estornos”" text="Tente outro termo." />

    variant: card (padrão, com quadriculado) · dashed (borda tracejada, para listas e tabelas) · plain (sem borda)
    color:   default · ai (ícone em âmbar, para áreas que a IA vai preencher)
    slots:   icon · actions · default (conteúdo extra abaixo do texto)
--}}
@props([
    'variant' => 'card',
    'color' => 'default',
    'icon' => null,
    'label' => null,
    'heading' => null,
    'text' => null,
    'actions' => null,
])

@php
    $isCard = $variant === 'card';
    $hasActions = $actions && $actions->isNotEmpty();
    $iconIsSlot = $icon instanceof \Illuminate\View\ComponentSlot;

    $iconToneClasses = $color === 'ai'
        ? 'border border-warn-line bg-warn-bg text-accent'
        : ($isCard ? 'border border-line bg-card text-ink' : 'bg-wrap text-ink-3');
@endphp

<div
    data-empty-state
    {{ $attributes->class([
        'relative flex flex-col items-center overflow-hidden text-center',
        'gap-3 rounded-card border border-line bg-card px-7 pt-10 pb-8' => $isCard,
        'gap-1.5 rounded-xl border border-dashed border-line-hover px-5 py-[22px]' => $variant === 'dashed',
        'gap-1.5 px-5 py-6' => $variant === 'plain',
    ]) }}
>
    @if ($isCard)
        <span aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 h-45 bg-grid-sm mask-fade-card"></span>
    @endif

    @if ($icon)
        <span @class([
            'relative flex shrink-0 items-center justify-center',
            'size-14 rounded-2xl shadow-[0_10px_24px_rgb(24_24_27/0.08)]' => $isCard,
            'mb-1 size-10 rounded-xl' => ! $isCard,
            $iconToneClasses,
        ])>
            @if ($iconIsSlot)
                {{ $icon }}
            @else
                <x-ui::icon :name="$icon" :class="$isCard ? 'size-6.5' : 'size-[18px]'" />
            @endif
        </span>
    @endif

    @if ($label)
        <span class="relative font-mono text-[11px] tracking-[.08em] text-muted uppercase">{{ $label }}</span>
    @endif

    @if ($heading)
        <h3 @class([
            'relative',
            'text-xl font-semibold tracking-[-0.01em]' => $isCard,
            'text-sm font-semibold' => ! $isCard,
        ])>{{ $heading }}</h3>
    @endif

    @if ($text)
        <p @class([
            'relative max-w-[400px]',
            'text-sm leading-[1.55] text-ink-3' => $isCard,
            'text-[13px] text-muted' => ! $isCard,
        ])>{{ $text }}</p>
    @endif

    @if ($slot->isNotEmpty())
        <div class="relative text-[13px] text-muted">{{ $slot }}</div>
    @endif

    @if ($hasActions)
        <div @class(['relative flex flex-wrap justify-center gap-2', 'mt-1.5' => $isCard, 'mt-2' => ! $isCard])>{{ $actions }}</div>
    @endif
</div>
