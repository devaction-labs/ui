{{--
    Comentário da x-ui::activity: cabeçalho com autor e quando, corpo e rodapé (reações).

    <x-ui::activity.comment name="Fernando Pinto" color="amber" when="há 1 h" edited>…</x-ui::activity.comment>
    <x-ui::activity.comment variant="ai" name="DevAction IA" verb="revisou o PR #142" when="há 2 h">
        <x-slot:badge><x-ui::badge color="risk" size="sm">2 problemas</x-ui::badge></x-slot:badge>
        …achados…
    </x-ui::activity.comment>

    verb:    texto depois do nome (padrão "comentou")
    role:    etiqueta à direita (Autor, Cliente)
    edited:  mostra "· editado"
    variant: default · ai (avatar da IA e fundo âmbar) · highlight (borda escura, ex.: comentário citado)
    Slots:   badge e actions (à direita do cabeçalho) · footer (reações, respostas)
--}}
@props([
    'name' => null,
    'color' => 'auto',
    'avatar' => null,
    'when' => null,
    'verb' => null,
    'role' => null,
    'edited' => false,
    'variant' => 'default',
])

@php
    $verb ??= __('commented');
    $isAi = $variant === 'ai';
@endphp

<li data-activity-comment class="relative flex gap-3.5">
    @if ($isAi)
        <x-ui::avatar icon="sparkles" color="ai" circle class="shrink-0 ring-4 ring-card" :aria-label="$name" />
    @else
        <x-ui::avatar :name="$name" :src="$avatar" :color="$color" circle class="shrink-0 ring-4 ring-card" />
    @endif

    <article {{ $attributes->class([
        'min-w-0 flex-1 overflow-hidden rounded-[14px] border bg-card',
        'border-line' => $variant === 'default',
        'border-warn-line bg-warn-bg/40' => $isAi,
        'border-ink shadow-[0_0_0_4px_rgb(24_24_27/0.06)]' => $variant === 'highlight',
    ]) }}>
        <header @class([
            'flex min-h-10 flex-wrap items-center gap-x-2 gap-y-1 border-b px-3.5 py-2 text-[13px]',
            'border-line bg-wrap text-muted' => ! $isAi,
            'border-warn-line text-warn-fg' => $isAi,
        ])>
            <b @class(['font-semibold', 'text-ink' => ! $isAi])>{{ $name }}</b>
            <span>{{ $verb }}@if ($when) {{ $when }}@endif</span>
            @if ($edited)
                <span>· {{ __('edited') }}</span>
            @endif
            <span class="ml-auto flex items-center gap-1.5">
                @if ($role)
                    <span class="inline-flex h-5 items-center rounded-full border border-line px-2 text-xs text-ink-3">{{ $role }}</span>
                @endif
                {{ $badge ?? '' }}
                {{ $actions ?? '' }}
            </span>
        </header>
        <div class="flex flex-col gap-2 px-4 py-3.5 leading-relaxed text-ink-2 [&_p]:m-0">
            {{ $slot }}
            @isset($footer)
                <div class="mt-1">{{ $footer }}</div>
            @endisset
        </div>
    </article>
</li>
