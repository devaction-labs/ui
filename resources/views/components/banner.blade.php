{{--
    Faixa de aviso no topo da página: novidade, instabilidade, cobrança, sem conexão.

    <x-ui::banner variant="warning" heading="API do GitHub instável." text="Reviews podem atrasar até 10 minutos." action="Status do GitHub" href="https://www.githubstatus.com" />
    <x-ui::banner variant="danger" heading="Seu plano vence em 3 dias." action="Renovar plano" wire:click="renew" dismissible>
        Depois disso, novos PRs não são revisados.
    </x-ui::banner>

    variant:     info (padrão) · warning · danger · offline
    action:      texto do link/botão à direita (com href vira link; sem href, os atributos wire:/x- vão para o botão)
    dismissible: botão de fechar; dispara banner-dismissed
    icon:        troca o ícone padrão da variante
--}}
@props([
    'variant' => 'info',
    'heading' => null,
    'text' => null,
    'action' => null,
    'href' => null,
    'icon' => null,
    'dismissible' => false,
])

@php
    [$toneClasses, $defaultIcon] = match ($variant) {
        'warning' => ['border-warn-line bg-warn-bg text-warn-fg', 'alert-02'],
        'danger' => ['border-risk-line bg-risk-bg text-risk-fg', 'alert-diamond'],
        'offline' => ['border-ink bg-ink text-on-ink', 'refresh'],
        default => ['border-info-line bg-info-bg text-info-fg', 'information-circle'],
    };

    $actionAttributes = $attributes->filter(fn ($value, string $key): bool => str_starts_with($key, 'wire:') || str_starts_with($key, 'x-on:') || str_starts_with($key, '@'));
    $rootAttributes = $attributes->except(array_keys($actionAttributes->getAttributes()));
@endphp

<div
    data-banner
    role="{{ $variant === 'danger' ? 'alert' : 'status' }}"
    @if ($dismissible) x-data="{ visible: true }" x-show="visible" @endif
    {{ $rootAttributes->class(['flex min-h-12 flex-wrap items-center gap-x-3 gap-y-1 rounded-xl border px-4 py-2.5 text-sm', $toneClasses]) }}
>
    <x-ui::icon :name="$icon ?? $defaultIcon" class="size-[18px] shrink-0" />
    <p class="min-w-0 flex-1 leading-snug">
        @if ($heading)
            <strong class="font-semibold">{{ $heading }}</strong>
        @endif
        {{ $text }}{{ $slot }}
    </p>
    @if ($action || $dismissible)
        <span class="ml-auto flex shrink-0 items-center gap-2.5">
            @if ($action && $href)
                <a href="{{ $href }}" class="font-medium underline underline-offset-[3px] hover:no-underline">{{ $action }}</a>
            @elseif ($action)
                <button type="button" {{ $actionAttributes->class(['cursor-pointer font-medium underline underline-offset-[3px] hover:no-underline']) }}>{{ $action }}</button>
            @endif
            @if ($dismissible)
                <button
                    type="button"
                    x-on:click="visible = false; $dispatch('banner-dismissed')"
                    aria-label="Fechar aviso"
                    class="flex size-7 cursor-pointer items-center justify-center rounded-md opacity-70 transition hover:opacity-100"
                >
                    <x-ui::icon name="cancel-01" class="size-4" />
                </button>
            @endif
        </span>
    @endif
</div>
