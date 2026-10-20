{{--
    Evento da x-ui::activity: uma linha no trilho (mudou de coluna, ligou um PR, adicionou um rótulo).

    <x-ui::activity.event icon="arrow-right-01" color="info" name="Fernando Pinto" when="ontem">
        moveu de A fazer para <x-ui::badge color="info" size="sm">Em andamento</x-ui::badge>
    </x-ui::activity.event>

    color:        plain (padrão) · info · ok · warn · risk (cor do ícone no trilho)
    avatar-color: cor do avatar de quem fez
--}}
@props([
    'icon' => 'information-circle',
    'color' => 'plain',
    'name' => null,
    'avatarColor' => 'auto',
    'when' => null,
])

<li data-activity-event {{ $attributes->class(['relative ml-2 flex items-center gap-3 text-[13px] text-muted']) }}>
    <span @class([
        'flex size-6 shrink-0 items-center justify-center rounded-full ring-4 ring-card',
        'bg-subtle text-ink-3' => $color === 'plain',
        'bg-info-bg text-info-fg' => $color === 'info',
        'bg-ok-bg text-ok-fg' => $color === 'ok',
        'bg-warn-bg text-warn-fg' => $color === 'warn',
        'bg-risk-bg text-risk-fg' => $color === 'risk',
    ])>
        <x-ui::icon :name="$icon" class="size-3.5" />
    </span>
    @if ($name)
        <x-ui::avatar :name="$name" :color="$avatarColor" size="xs" circle class="shrink-0" />
    @endif
    <span class="min-w-0 leading-normal">
        @if ($name)
            <b class="font-semibold text-ink">{{ $name }}</b>
        @endif
        {{ $slot }}
        @if ($when)
            <span class="whitespace-nowrap">{{ $when }}</span>
        @endif
    </span>
</li>
