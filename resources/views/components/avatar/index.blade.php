{{--
    Avatar (API no estilo Flux): pessoas, workspaces, repositórios e o agente.

    <x-ui::avatar name="Alex Nogueira" color="auto" circle />
    <x-ui::avatar src="/fotos/alex.jpg" name="Alex Nogueira" size="lg" circle />
    <x-ui::avatar name="Carlos André" circle badge badge:color="green" badge:label="Online" />
    <x-ui::avatar name="DevAction" color="ink" />
    <x-ui::avatar icon="sparkles" color="ai" circle aria-label="Agente IA" />

    size:   xs (24) · sm (32) · base (40, padrão) · lg (56) · xl (72)
    color:  nomes do Tailwind (red, amber, green, blue, indigo…) mapeados para os tokens,
            auto (estável pelo nome ou por color:seed), ink, ai e outline
    badge:  true vira um ponto de status; texto vira um contador; também aceita slot
--}}
@props([
    'name' => null,
    'initials' => null,
    'src' => null,
    'alt' => null,
    'size' => 'base',
    'color' => null,
    'circle' => false,
    'icon' => null,
    'tooltip' => null,
    'badge' => null,
    'href' => null,
    'as' => 'div',
])

@php
    $originalAttributes = $attributes;
    $tooltipPosition = $attributes->get('tooltip:position', 'top');
    $colorSeed = $attributes->get('color:seed');
    $badgeColor = $attributes->get('badge:color');
    $badgePosition = $attributes->get('badge:position', 'bottom right');
    $badgeCircle = $attributes->get('badge:circle');
    $badgeVariant = $attributes->get('badge:variant', 'solid');
    $badgeLabel = $attributes->get('badge:label');
    $attributes = $attributes->except(['color:seed', 'badge:color', 'badge:position', 'badge:circle', 'badge:variant', 'badge:label', 'tooltip:position']);

    $toneFor = fn (?string $colorName): string => match ($colorName) {
        'red', 'rose', 'pink' => 'risk',
        'orange', 'amber', 'yellow' => 'warn',
        'lime', 'green', 'emerald', 'teal' => 'ok',
        'cyan', 'sky', 'blue' => 'info',
        'indigo', 'violet', 'purple', 'fuchsia' => 'indigo',
        'ink', 'ai', 'outline' => $colorName,
        default => 'plain',
    };

    if ($color === 'auto') {
        $autoTones = ['indigo', 'ok', 'risk', 'warn', 'info'];
        $tone = $autoTones[crc32((string) ($colorSeed ?? $name ?? $initials)) % count($autoTones)];
    } else {
        $tone = $toneFor($color);
    }

    if ($initials === null && filled($name)) {
        $words = preg_split('/\s+/', trim($name));
        $initialWords = count($words) > 1 ? [reset($words), end($words)] : [reset($words)];
        $initials = implode('', array_map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)), $initialWords));
    }

    $toneClasses = match ($tone) {
        'risk' => 'bg-risk-bg text-risk-fg',
        'warn' => 'bg-warn-bg text-warn-fg',
        'ok' => 'bg-ok-bg text-ok-fg',
        'info' => 'bg-info-bg text-info-fg',
        'indigo' => 'bg-indigo-bg text-indigo-fg',
        'ink' => 'bg-ink text-on-ink',
        'ai' => 'border-[1.5px] border-ai bg-warn-bg text-accent',
        'outline' => 'border-[1.5px] border-dashed border-muted text-muted',
        default => 'bg-plain-bg text-plain-fg',
    };

    [$sizeClasses, $textClasses, $radiusClasses, $iconClasses, $dotClasses] = match ($size) {
        'xs' => ['size-6', 'text-[9px]', 'rounded-md', 'size-3.5', 'size-2'],
        'sm' => ['size-8', 'text-[11px]', 'rounded-lg', 'size-4', 'size-2.5'],
        'lg' => ['size-14', 'text-base', 'rounded-[14px]', 'size-6', 'size-3.5'],
        'xl' => ['size-18', 'text-xl', 'rounded-[18px]', 'size-8', 'size-4'],
        default => ['size-10', 'text-xs', 'rounded-[11px]', 'size-4.5', 'size-3'],
    };

    $badgeTone = $toneFor($badgeColor);
    $badgeIsSlot = $badge instanceof \Illuminate\View\ComponentSlot;
    $badgeIsDot = $badge === true || $badge === '' || ($badgeIsSlot && $badge->isEmpty());

    $badgeSolidClasses = match ($badgeTone) {
        'risk' => 'bg-missing text-card',
        'warn' => 'bg-ai text-card',
        'ok' => 'bg-ok-fg text-card',
        'info' => 'bg-learning text-card',
        'indigo' => 'bg-violet text-white',
        'plain' => $badgeIsDot ? 'bg-muted' : 'bg-ink text-on-ink',
        default => 'bg-ink text-on-ink',
    };

    $badgeOutlineClasses = match ($badgeTone) {
        'risk' => 'border border-risk-line bg-card text-risk-fg',
        'warn' => 'border border-warn-line bg-card text-warn-fg',
        'ok' => 'border border-ok-line bg-card text-ok-fg',
        'info' => 'border border-info-line bg-card text-info-fg',
        default => 'border border-line bg-card text-ink-2',
    };

    $badgePositionClasses = match ($badgePosition) {
        'top left' => '-top-0.5 -left-0.5',
        'top right' => '-top-0.5 -right-0.5',
        'bottom left' => '-bottom-0.5 -left-0.5',
        default => '-right-0.5 -bottom-0.5',
    };

    $tag = $href ? 'a' : ($as === 'button' ? 'button' : 'div');
    $tooltipText = $tooltip === true ? $name : $tooltip;
    $hasContent = $slot->isNotEmpty();
@endphp

@if ($tooltipText)
    <x-ui::tooltip :content="$tooltipText" :position="$tooltipPosition">
        <x-ui::avatar
            :name="$name"
            :initials="$initials"
            :src="$src"
            :alt="$alt"
            :size="$size"
            :color="$color"
            :circle="$circle"
            :icon="$icon"
            :badge="$badge"
            :href="$href"
            :as="$as"
            :attributes="$originalAttributes->except('tooltip:position')"
        >{{ $slot }}</x-ui::avatar>
    </x-ui::tooltip>
@else
<{{ $tag }}
    @if ($href) href="{{ $href }}" @endif
    @if ($tag === 'button') type="button" @endif
    data-avatar
    {{ $attributes->class([
        'relative inline-flex shrink-0 items-center justify-center font-mono font-medium select-none',
        $sizeClasses,
        $textClasses,
        $circle ? 'rounded-full' : $radiusClasses,
        $src ? 'bg-plain-bg' : $toneClasses,
        'cursor-pointer outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-wrap' => $tag !== 'div',
    ]) }}
>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $alt ?? $name ?? '' }}" class="size-full object-cover {{ $circle ? 'rounded-full' : $radiusClasses }}" />
    @elseif ($hasContent)
        {{ $slot }}
    @elseif ($icon)
        <x-ui::icon :name="$icon" :class="$iconClasses" />
    @elseif ($initials)
        <span @if ($name) aria-hidden="true" @endif>{{ $initials }}</span>
        @if ($name)
            <span class="sr-only">{{ $name }}</span>
        @endif
    @else
        <x-ui::icon name="user" :class="$iconClasses" />
    @endif

    @if ($badge !== null && $badge !== false)
        <span
            @if ($badgeLabel) role="img" aria-label="{{ $badgeLabel }}" @elseif ($badgeIsDot) aria-hidden="true" @endif
            @class([
                'absolute flex items-center justify-center ring-2 ring-card',
                $badgePositionClasses,
                $badgeVariant === 'outline' ? $badgeOutlineClasses : $badgeSolidClasses,
                $dotClasses.' rounded-full' => $badgeIsDot,
                'h-4 min-w-4 px-1 font-sans text-[10px] font-semibold leading-none' => ! $badgeIsDot,
                ($badgeCircle || $badgeIsSlot ? 'rounded-full' : 'rounded-[5px]') => ! $badgeIsDot,
            ])
        >@unless ($badgeIsDot){{ $badge }}@endunless</span>
    @endif
</{{ $tag }}>
@endif
