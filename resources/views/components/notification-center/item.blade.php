{{--
    Notificação. Clicar marca como lida (dispara notification-read com o id); wire:click e href continuam valendo.

    <x-ui::notification-center.item id="12" type="mention" icon="at" color="info" unread meta="SGE · M3 · há 1 h" href="/tarefas/12">
        Carlos André mencionou você em "Relatórios financeiros"
    </x-ui::notification-center.item>

    color: warn · info · ok · risk · plain (padrão) — cor do ícone
--}}
@props([
    'type' => null,
    'icon' => 'notification-01',
    'color' => 'plain',
    'unread' => false,
    'meta' => null,
    'href' => null,
])

@php
    $toneClasses = match ($color) {
        'warn' => 'bg-warn-bg text-warn-fg',
        'info' => 'bg-info-bg text-info-fg',
        'ok' => 'bg-ok-bg text-ok-fg',
        'risk' => 'bg-risk-bg text-risk-fg',
        default => 'bg-plain-bg text-ink-2',
    };
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    data-notification
    data-type="{{ $type }}"
    data-unread="{{ $unread ? 'true' : 'false' }}"
    @if ($href) href="{{ $href }}" @else type="button" @endif
    x-show="matches($el)"
    x-on:click="read($el)"
    x-bind:class="isUnread($el) ? 'bg-ai-soft' : 'bg-transparent'"
    {{ $attributes->class(['flex w-full cursor-pointer gap-3 px-4 py-3 text-left transition hover:bg-subtle']) }}
>
    <span class="flex size-8 shrink-0 items-center justify-center rounded-[10px] {{ $toneClasses }}">
        <x-ui::icon :name="$icon" class="size-4" />
    </span>
    <span class="min-w-0 flex-1">
        <span class="block text-sm leading-[1.45]" x-bind:class="isUnread($el.closest('[data-notification]')) ? 'font-medium' : 'font-normal'">{{ $slot }}</span>
        @if ($meta)
            <span class="mt-[3px] block text-xs text-muted">{{ $meta }}</span>
        @endif
    </span>
    <span x-show="isUnread($el.closest('[data-notification]'))" class="mt-1.5 size-2 shrink-0 rounded-full bg-ai" aria-hidden="true"></span>
    <span class="sr-only" x-text="isUnread($el.closest('[data-notification]')) ? @js(__('unread')) : ''"></span>
</{{ $tag }}>
