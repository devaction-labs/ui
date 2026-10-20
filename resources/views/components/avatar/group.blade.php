{{--
    Avatares sobrepostos, com presença (badge de cada avatar) e +N automático.

    <x-ui::avatar.group max="3" label="Time do SGE · 2 online">
        <x-ui::avatar name="Alex Nogueira" color="auto" size="sm" circle badge badge:color="green" badge:label="Online" />
        <x-ui::avatar name="Carlos André" color="auto" size="sm" circle />
        …
    </x-ui::avatar.group>

    max:   mostra só os primeiros e junta o resto num avatar "+N"
    size:  tamanho do +N e da sobreposição: xs · sm (padrão) · base · lg
    label: texto ao lado do grupo
    O anel usa a cor do card; para outro fundo, troque com *:ring-*.
--}}
@props([
    'max' => null,
    'size' => 'sm',
    'label' => null,
])

@php
    $total = substr_count((string) $slot, 'data-avatar'."\n") + substr_count((string) $slot, 'data-avatar ');
    $hidden = $max !== null ? max(0, $total - (int) $max) : 0;

    $overlap = match ($size) {
        'xs' => '-space-x-1.5',
        'base' => '-space-x-2.5',
        'lg' => '-space-x-3.5',
        default => '-space-x-2',
    };
@endphp

<div data-avatar-group {{ $attributes->class(['flex items-center gap-3']) }}>
    <div
        @class(['flex items-center', $overlap, '*:ring-2 *:ring-card'])
        @if ($hidden > 0) x-data x-init="[...$el.children].slice({{ (int) $max }}, {{ $total }}).forEach((avatar) => avatar.hidden = true)" @endif
    >
        {{ $slot }}
        @if ($hidden > 0)
            <x-ui::avatar :size="$size" circle :aria-label="__(':count more people', ['count' => $hidden])">+{{ $hidden }}</x-ui::avatar>
        @endif
    </div>
    @if ($label)
        <span class="text-[13px] text-ink-3">{{ $label }}</span>
    @endif
</div>
